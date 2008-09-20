<?php
// Class for accessing XML data through the XPath language.
class XML {

	var $nodes    = array();		// This array contains a list of all document nodes saved as an associative array.
	var $ids      = array();		// This array contains a list of all IDs of all document nodes that are used for counting when adding a new node.
	var $path     = "";				// This variable saves the current path while parsing a XML file and adding the nodes being read from the file.
	var $position = 0;				// This variable counts the current document position while parsing a XML file and adding the nodes being read from the file.
	var $root     = "";				// This string contains the full path to the node that acts as the root node of the whole document.
	var $xpath    = "";				// This string contains the full XPath expression being parsed currently.
	
	// This array contains a list of entities to be converted when an XPath expression is evaluated.
	var $entities = array ( "&" => "&amp;", "<" => "&lt;", ">" => "&gt;", "'" => "&apos", '"' => "&quot;" );

	// This array contains a list of all valid axes that can be evaluated in an XPath expression.
	var $axes = array ( "child", "descendant", "parent", "ancestor", "following-sibling", "preceding-sibling", "following", "preceding", "attribute", "namespace", "self", "descendant-or-self", "ancestor-or-self" );
	var $axis = array();
	
	// This array contains a list of all valid functions that can be evaluated in an XPath expression.
	var $functions = array ( "last", "position", "count", "id", "name", "string", "concat", "starts-with", "contains", "substring-before", "substring-after", "substring", "string-length", "translate", "boolean", "not", "true", "false", "lang", "number", "sum", "floor", "ceiling", "round", "text" );

	// This array contains a list of all valid operators that can be evaluated in a predicate of an XPath expression. 
	// The list is ordered by the precedence of the operators (lowest precedence first).
	var $operators = array( " or ", " and ", "=", "!=", "<=", "<", ">=", ">", "+", "-", "*", " div ", " mod " );

	function XML ($src = 'string', $file = "" ) {											// Constructor of the class.
		if ($src == 'string') {
			$content = $file;
			if ( !empty($content) ) {
				$parser = xml_parser_create();
				xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
				xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
				//xml_set_object($parser, &$this);
				xml_set_object($parser, $this);
				xml_set_element_handler($parser, "handle_start_element", "handle_end_element");
				xml_set_character_data_handler($parser, "handle_character_data");
				if ( !xml_parse($parser, $content, true) ) {
					$this->display_error("XML error in file %s, line %d: %s", $file, xml_get_current_line_number($parser), xml_error_string(xml_get_error_code($parser)));
				}
				xml_parser_free($parser);
			}
		} else {
			if ( !empty($file) ) {
				$this->load_file($file);
			}
		}
	}

	function load_file ( $file ) {										// Reads a file and parses the XML data.
		if ( file_exists($file) && is_readable($file) ) {
			$content = implode("", file($file));
			if ( !empty($content) ) {
				$parser = xml_parser_create();
				xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
				xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
				//xml_set_object($parser, &$this);
				xml_set_object($parser, $this);
				xml_set_element_handler($parser, "handle_start_element", "handle_end_element");
				xml_set_character_data_handler($parser, "handle_character_data");
				if ( !xml_parse($parser, $content, true) ) {
					$this->display_error("XML error in file %s, line %d: %s", $file, xml_get_current_line_number($parser), xml_error_string(xml_get_error_code($parser)));
				}
				xml_parser_free($parser);
			}
		} else {
			$this->display_error("File %s could not be found or read.", $file);
		}
	}

	// Generates a XML file with the content of the current document.
	function get_file ( $highlight = array(), $root = "", $level = 0 ) {
		$xml = "";
		$highlight_start = "<font color=\"#FF0000\"><b>";
		$highlight_end   = "</b></font>";
		$before = "";
		for ( $i = 0; $i < ( $level * 2 ); $i++ ) {
			$before .= " ";
		}
		if ( empty($root) ) {
			$root = $this->root;
		}
		$selected = in_array($root, $highlight);
		$xml .= $before;
		if ( $selected ) {
			$xml .= $highlight_start;
		}
		$xml .= "&lt;".$this->nodes[$root]["name"];
		if ( count($this->nodes[$root]["attributes"]) > 0 ) {
			foreach ( $this->nodes[$root]["attributes"] as $key => $value ) {
				if ( in_array($root."/attribute::".$key, $highlight) ) {
					$xml .= $highlight_start;
				}
				$xml .= " ".$key."=\"".trim(stripslashes($value))."\"";
				if ( in_array($root."/attribute::".$key, $highlight) ) {
					$xml .= $highlight_end;
				}
			}
		}
		if ( empty($this->nodes[$root]["text"]) && !isset($this->nodes[$root]["children"]) ) {
			$xml .= "/";
		}
		$xml .= "&gt;\n";
		if ( $selected ) {
			$xml .= $highlight_end;
		}
		if ( !empty($this->nodes[$root]["text"]) ) {
			$xml .= $before."  ".$this->nodes[$root]["text"]."\n";
		}
		if ( isset($this->nodes[$root]["children"]) ) {
			foreach ( $this->nodes[$root]["children"] as $child => $pos ) {
				for ( $i = 1; $i <= $pos; $i++ ) {
					$fullchild = $root."/".$child."[".$i."]";
					$xml .= $this->get_file($highlight, $fullchild, $level + 1);
				}
			}
		}
		if ( !empty($this->nodes[$root]["text"]) || isset($this->nodes[$root]["children"]) ) {
			$xml .= $before;
			if ( $selected ) {
				$xml .= $highlight_start;
			}
			$xml .= "&lt;/".$this->nodes[$root]["name"]."&gt;";
			if ( $selected ) {
				$xml .= $highlight_end;
			}
			$xml .= "\n";
		}
		return $xml;
	}

	// Adds a new node to the XML document.
	function add_node ( $context, $name ) {
		if ( empty($this->root) ) {
			$this->root = "/" . $name."[1]";
		}
		$path = $context . "/" . $name;
		if (! isset($this->ids[$path])) {
			$this->ids[$path] = 0;
		}
		$position = ++$this->ids[$path];
		$relative = $name . "[" . $position . "]";
		$fullpath = $context . "/" . $relative;
		$this->nodes[$fullpath]['context-position'] = $position;
		if (!isset($this->nodes[$context]['document_position'])) {
			$this->nodes[$context]['document_position'] = 0;
		}
		$this->nodes[$fullpath]['document_position'] = $this->nodes[$context]['document_position'] + 1;
		$this->nodes[$fullpath]['name']   = $name;
		$this->nodes[$fullpath]['text']   = "";
		$this->nodes[$fullpath]['parent'] = $context;
		if ( ! isset($this->nodes[$context]["children"][$name])) {
			$this->nodes[$context]["children"][$name] = 1;
		} else {
			$this->nodes[$context]["children"][$name] = $this->nodes[$context]["children"][$name] + 1;
		}
		return $fullpath;
	}

	// Removes a node from the XML document.
	function remove_node ( $node ) {
		if ( ereg("/attribute::", $node) ) {
			$parent = $this->prestr($node, "/attribute::");
			$attribute = $this->afterstr($node, "/attribute::");
			if ( isset($this->nodes[$parent]["attributes"][$attribute]) ) {
				$new = array();
				foreach ( $this->nodes[$parent]["attributes"] as $key => $value ) {
					if ( $key != $attribute ) {
						$new[$key] = $value;
					}
				}
				$this->nodes[$parent]["attributes"] = $new;
			}
		} else {
			$rename = array();
			$name     = $this->nodes[$node]["name"];
			$parent   = $this->nodes[$node]["parent"];
			$siblings = $this->nodes[$parent]["children"][$name];
			$this->nodes[$parent]["children"][$name]--;
			$counter = 1;
			for ( $i = 1; $i <= $siblings; $i++ ) {
				$sibling = $parent . "/" . $name . "[" . $i . "]";
				if ( $sibling != $node ) {
					$new = $parent."/".$name."[".$counter."]";
					$counter++;
					$rename[$sibling] = $new;
				}
			}
			$nodes = array();
			foreach ( $this->nodes as $name => $values ) {
				$position = strpos($name, $node);
				if ( $position === false ) {
					foreach ( $rename as $old => $new ) {
						$name             = str_replace($old, $new, $name);
						$values["parent"] = str_replace($old, $new, $values["parent"]);
					}
					$nodes[$name] = $values;
				}
			}
			$this->nodes = $nodes;
		}
	}

	function add_content ( $path, $value ) {												// Add content to a node.
		if ( ereg("/attribute::", $path) ) {
			$parent = $this->prestr($path, "/attribute::");
			$parent = $this->nodes[$parent];
			$attribute = $this->afterstr($path, "/attribute::");
			$parent["attributes"][$attribute] .= $value;
		} else {
			$this->nodes[$path]["text"] .= $value;
		}
	}

	function set_content ( $path, $value ) {												// Set the content of a node.
		if ( ereg("/attribute::", $path) ) {
			$parent = $this->prestr($path, "/attribute::");
			$parent = $this->nodes[$parent];
			$attribute = $this->afterstr($path, "/attribute::");
			$parent["attributes"][$attribute] = $value;
		} else {
			$this->nodes[$path]["text"] = $value;
		}
	}

	function get_content ( $path ) {															// Retrieves the content of a node.
		if ( ereg("/attribute::", $path) ) {
			$parent = $this->prestr($path, "/attribute::");
			$parent = $this->nodes[$parent];
			$attribute = $this->afterstr($path, "/attribute::");
			$attribute = $parent["attributes"][$attribute];
			return $attribute;
		} else {
			return stripslashes($this->nodes[$path]["text"]);
		}
	}

	function add_attributes ( $path, $attributes ) {									// Add attributes to a node.
		$this->nodes[$path]["attributes"] = array_merge($attributes, $this->nodes[$path]["attributes"]);
	}

	function set_attributes ( $path, $attributes ) {									// Sets the attributes of a node.
		$this->nodes[$path]["attributes"] = $attributes;
	}

	function get_attributes ($path) {														// Retrieves a list of all attributes of a node.
		return $this->nodes[$path]["attributes"];
	}

	function get_name ($path) {																// Retrieves the name of a document node.
		return $this->nodes[$path]["name"];
	}

	function evaluate ( $path, $context = "" ) {											// Evaluates an XPath expression.
		$path = stripslashes($path);
		$path = str_replace("\"", "", $path);
		$path = str_replace("'", "", $path);
		$paths = $this->split_paths($path);
		$result = array();

		foreach ( $paths as $path ) {
			$path = trim($path);
			$this->xpath = $path;
			$path = strtr($path, array_flip($this->entities));
			$steps = $this->split_steps($path);
			if ( empty($steps[0]) ) {
				array_shift($steps);
			}
			$nodes = $this->evaluate_step($context, $steps);
			$nodes = array_unique($nodes);
			$result = array_merge($result, $nodes);
		}
		return $result;
	}

	function handle_start_element ( $parser, $name, $attributes ) {				// Handles opening XML tags while parsing.
		$this->path = $this->add_node($this->path, $name);
		$this->set_attributes($this->path, $attributes);
	}

	function handle_end_element ( $parser, $name ) {									// Handles closing XML tags while parsing.
		$this->path = substr($this->path, 0, strrpos($this->path, "/"));
	}

	function handle_character_data ( $parser, $text ) {								// Handles character data while parsing.
		$text = strtr($text, $this->entities);
		$this->add_content($this->path, addslashes(trim($text)));
	}

	function split_paths ($expression) {													// Splits an XPath expression into its different expressions.
		$paths = array();
		$position = -1;
		do {
			$position = $this->search_string($expression, "|");
			if ($position >= 0 ) {
				$left  = substr($expression, 0, $position);
				$right = substr($expression, $position + 1);
				$paths[] = $left;
				$expression = $right;
			}
		} while ($position > -1);

		$paths[] = $expression;
		return $paths;
	}

	// Splits an XPath expression into its different steps.
	function split_steps ( $expression ) {
		$steps = array();
		$expression = str_replace("//@", "/descendant::*/@", $expression);
		$expression = str_replace("//", "/descendant::", $expression);
		$position = -1;
		do {
			$position = $this->search_string($expression, "/");
			if ( $position >= 0 ) {
				$left  = substr($expression, 0, $position);
				$right = substr($expression, $position + 1);
				$steps[] = $left;
				$expression = $right;
			}
		} while ( $position > -1 );
		$steps[] = $expression;
		return $steps;
	}

	// Retrieves axis information from an XPath expression step.
	function get_axis ( $step, $node ) {
		$axis = array("axis" => "", "node-test" => "", "predicate" => array());
		if ( ereg("\[", $step) ) {
			$predicates = substr($step, strpos($step, "["));
			$step = $this->prestr($step, "[");
			$predicates = str_replace("][", "]|[", $predicates);
			$predicates = explode("|", $predicates);
			foreach ( $predicates as $predicate ) {
				$predicate = substr($predicate, 1, strlen($predicate) - 2);
				$axis["predicate"][] = $predicate;
			}
		}
		if ( $this->search_string($step, "::") > -1 ) {
			$axis["axis"]      = $this->prestr($step, "::");
			$axis["node-test"] = $this->afterstr($step, "::");
		} else {
			if ( empty($step) ) {
				$step = ".";
			}
			if ( $step == "*" ) {
				$axis["axis"]      = "child";
				$axis["node-test"] = "*";
			} elseif ( ereg("\(", $step) ) {
				if ( $this->is_function($this->prestr($step, "(")) ) {
					$start = strpos($step, "(");
					$end   = strpos($step, ")", $start);
					$before  = substr($step, 0, $start);
					$between = substr($step, $start + 1, $end - $start - 1);
					$after   = substr($step, $end + 1);
					$before  = trim($before);
					$between = trim($between);
					$after   = trim($after);
					$axis["axis"]      = "function";
					$axis["node-test"] = $this->evaluate_function($before, $between, $node);
				} else {
					$axis["axis"]      = "child";
					$axis["node-test"] = $step;
				}
			} elseif ( eregi("^@", $step) ) {
				$axis["axis"]      = "attribute";
				$axis["node-test"] = substr($step, 1);
			} elseif ( eregi("\]$", $step) ) {
				$axis["axis"]      = "child";
				$axis["node-test"] = substr($step, strpos($step, "["));
			} elseif ( $step == "." ) {
				$axis["axis"]      = "self";
				$axis["node-test"] = "*";
			} elseif ( $step == ".." ) {
				$axis["axis"]      = "parent";
				$axis["node-test"] = "*";
			} elseif ( ereg("^[a-zA-Z0-9\-_]+$", $step) ) {
				$axis["axis"]      = "child";
				$axis["node-test"] = $step;
			} else {
				$axis["axis"]      = "child";
				$axis["node-test"] = $step;
			}
		}

		if ( !in_array($axis["axis"], array_merge($this->axes, array("function"))) ) {
			$this->display_error("While parsing an XPath expression, in the step \"%s\" the invalid axis \"%s\" was found.", str_replace($step, "<b>".$step."</b>", $this->xpath), $axis["axis"]);
		}
		return $axis;
	}

	// Looks for a string within another string.
	function search_string ( $term, $expression ) {
		$brackets = 0;
		for ( $i = 0; $i < strlen($term); $i++ ) {
			$character = substr($term, $i, 1);
			if ( ( $character == "(" ) || ( $character == "[" ) ) {
				$brackets++;
			} elseif ( ( $character == ")" ) || ( $character == "]" ) ) {
				$brackets--;
			} elseif ( $brackets == 0 ) {
				if ( substr($term, $i, strlen($expression)) == $expression ) {
					return $i;
				}
			}
		}
		if ( $brackets != 0 ) {
			$this->display_error("While parsing an XPath expression, in the predicate \"%s\", there was an invalid number of brackets.", str_replace($term, "<b>".$term."</b>", $this->xpath));
		}
		return (-1);
	}

	// Checks for a valid function name.
	function is_function ( $expression ) {
		if ( in_array($expression, $this->functions) ) {
			return true;
		} else {
			return false;
		}
	}

	function evaluate_step ( $context, $steps ) {										// Evaluates a step of an XPath expression.
		$nodes = array();
		if ( is_array($context) ) {
			foreach ( $context as $path ) {
				$nodes = array_merge($nodes, $this->evaluate_step($path, $steps));
			}
		} else {
			$step = array_shift($steps);
			$contexts = array();
			$axis = $this->get_axis($step, $context);
			if ( $axis["axis"] == "function" ) {
				if ( is_array($axis["node-test"]) ) {
					$contexts = array_merge($contexts, $axis["node-test"]);
				} else {
					$contexts[] = $axis["node-test"];
				}
			} else {
				$method = "handle_axis_".str_replace("-", "_", $axis["axis"]);
				//if ( !method_exists(&$this, $method) ) {
				if ( !method_exists($this, $method) ) {
					$this->display_error("While parsing an XPath expression, the axis \"%s\" could not be handled, because this version does not support this axis.", $axis["axis"]);
				}
				//$contexts = call_user_method($method, &$this, $axis, $context);
				//$contexts = call_user_method($method, $this, $axis, $context);
				$contexts = call_user_func(array($this,$method), $axis, $context);
				if ( count($axis["predicate"]) > 0 ) {
					$contexts = $this->check_predicates($contexts, $axis["predicate"]);
				}
			}
			if ( count($steps) > 0 ) {
				$nodes = $this->evaluate_step($contexts, $steps);
			} else {
				$nodes = $contexts;
			}
		}
		return $nodes;
	}

	// Evaluates an XPath function
	function evaluate_function ( $function, $arguments, $node ) {
		$function  = trim($function);
		$arguments = trim($arguments);
		$method = "handle_function_".str_replace("-", "_", $function);
		if ( !method_exists($this, $method) ) {
			$this->display_error("While parsing an XPath expression, the function \"%s\" could not be handled, because this version does not support this function.", $function);
		}
		return call_user_method($method, $this, $node, $arguments);
	}

	// Evaluates a predicate on a node.
	function evaluate_predicate ( $node, $predicate ) {
		$position = 0;
		$operator = "";
		foreach ( $this->operators as $expression ) {
			if ( $position <= 0 ) {
				$position = $this->search_string($predicate, $expression);
				if ( $position > 0 ) {
					$operator = $expression;
					if ( $operator == "=" ) {
						if ( $this->search_string($predicate, "!=") == ( $position - 1 ) ) {
							$position = $this->search_string($predicate, "!=");
							$operator = "!=";
						}
						if ( $this->search_string($predicate, "<=") == ( $position - 1 ) ) {
							$position = $this->search_string($predicate, "<=");
							$operator = "<=";
						}
						if ( $this->search_string($predicate, ">=") == ( $position - 1 ) ) {
							$position = $this->search_string($predicate, ">=");
							$operator = ">=";
						}
					}
				}
			}
		}
		if ( $operator == "-" ) {
			foreach ( $this->functions as $function ) {
				if ( ereg("-", $function) ) {
					$sign = strpos($function, "-");
					$sub = substr($predicate, $position - $sign, strlen($function));
					if ( $sub == $function ) {
						$operator = "";
						$position = -1;
					}
				}
			}
		} elseif ( $operator == "*" ) {
			$character = substr($predicate, $position - 1, 1);
			$attribute = substr($predicate, $position - 11, 11);
			if ( ( $character == "@" ) || ( $attribute == "attribute::" ) ) {
				$operator = "";
				$position = -1;
			}
		}
		if ( $position > 0 ) {
			$left  = substr($predicate, 0, $position);
			$right = substr($predicate, $position + strlen($operator));
			$left  = trim($left);
			$right = trim($right);
			$left  = $this->evaluate_predicate($node, $left);
			$right = $this->evaluate_predicate($node, $right);
			switch ($operator) {
				case " or ":
					return ( $left or $right );
				case " and ":
					return ( $left and $right );
				case "=":
					return ( $left == $right );
				case "!=":
					return ( $left != $right );
				case "<=":
					return ( $left <= $right );
				case "<":
					return ( $left < $right );
				case ">=":
					return ( $left >= $right );
				case ">":
					return ( $left > $right );
				case "+":
					return ( $left + $right );
				case "-":
					return ( $left - $right );
				case "*":
					return ( $left * $right );
				case " div ":
					if ( $right == 0 ) {
						$this->display_error("While parsing an XPath predicate, a error due a division by zero occured.");
					} else {
						return ( $left / $right );
					}
					break;
				case " mod ":
					return ( $left % $right );
			}
		}

		if ( ereg("\(", $predicate) ) {
			$start = strpos($predicate, "(");
			$end   = strpos($predicate, ")", $start);
			$before  = substr($predicate, 0, $start);
			$between = substr($predicate, $start + 1, $end - $start - 1);
			$after   = substr($predicate, $end + 1);
			$before  = trim($before);
			$between = trim($between);
			$after   = trim($after);
			if ( !empty($after) ) {
				$this->display_error("While parsing an XPath expression there was found an error in the predicate \"%s\", because after a closing bracket there was found something unknown.", str_replace($predicate, "<b>".$predicate."</b>", $this->xpath));
			}
			if ( empty($before) && empty($after) ) {
				return $this->evaluate_predicate($node, $between);
			} elseif ( $this->is_function($before) ) {
				return $this->evaluate_function($before, $between, $node);
			} else {
				$this->display_error("While parsing a predicate in an XPath expression, a function \"%s\" was found, which is not yet supported by the parser.", str_replace($before,"<b>".$before."</b>", $this->xpath));
			}
		}

		if ( ereg("^[0-9]+(\.[0-9]+)?$", $predicate) || ereg("^\.[0-9]+$", $predicate) ) {
			return doubleval($predicate);
		}

		$result = $this->evaluate($predicate, $node);
		if ( count($result) > 0 ) {
			$result = explode("|", implode("|", $result));
			$value = $this->get_content($result[0]);
			return $value;
		}
		return $predicate;
	}

	// Checks whether a node matches predicates.
	function check_predicates ( $nodes, $predicates ) {
		$result = array();
		foreach ( $nodes as $node ) {
			$add = true;
			foreach ( $predicates as $predicate ) {
				if ( ereg("^[0-9]+$", $predicate) ) {
					$predicate .= "=position()";
				}
				$check = $this->evaluate_predicate($node, $predicate);
				if ( is_string($check) && ( ( $check == "" ) || ( $check == $predicate ) ) ) {
					$check = false;
				}
				if ( is_int($check) ) {
					if ( $check == $this->handle_function_position($node, "") ) {
						$check = true;
					} else {
						$check = false;
					}
				}
				$add = $add && $check;
			}
			if ( $add ) {
				$result[] = $node;
			}
		}
		return $result;
	}

	// Checks whether a node matches a node-test.
	function check_node_test ( $context, $node_test ) {
		global $axis;
		if ( ereg("\(", $node_test) ) {
			$function = $this->prestr($node_test, "(");
			switch ($function) {
				case "node":
					return true;
				case "text":
					if ( !empty($this->nodes[$context]["text"]) ) {
						return true;
					}
					break;
				case "comment":
					if ( !empty($this->nodes[$context]["comment"]) ) {
						return true;
					}
					break;
				case "processing-instruction":
					$literal = $this->afterstr($axis["node-test"], "(");
					$literal = substr($literal, 0, strlen($literal) - 1);
					if ( !empty($literal) ) {
						if ( $this->nodes[$context]["processing-instructions"] == $literal ) {
							return true;
						}
					} else {
						if ( !empty($this->nodes[$context]["processing-instructions"]) ) {
							return true;
						}
					}
					break;
				default:
					$this->display_error("While parsing an XPath expression there was found an undefined function called \"%s\".",str_replace($function, "<b>".$function."</b>",$this->xpath));
			}
		} elseif ( $node_test == "*" ) {
			return true;
		} elseif ( ereg("^[a-zA-Z0-9\-_]+", $node_test) ) {
			if ( $this->nodes[$context]["name"] == $node_test ) {
				return true;
			}
		} else {
			$this->display_error("While parsing the XPath expression \"%s\" an empty and therefore invalid node-test has been found.",$this->xpath);
		}
		return false;
	}

	// Handles the XPath child axis.
	function handle_axis_child ( $axis, $context ) {
		$nodes = array();
		$children = $this->nodes[$context]["children"];
		if ( !empty($children) ) {
			foreach ( $children as $child_name => $child_position ) {
				for ( $i = 1; $i <= $child_position; $i++ ) {
					$child = $context."/".$child_name."[".$i."]";
					if ( $this->check_node_test($child, $axis["node-test"]) ) {
						$nodes[] = $child;
					}
				}
			}
		}
		return $nodes;
	}

	// Handles the XPath parent axis.
	function handle_axis_parent ( $axis, $context ) {
		$nodes = array();
		if ( $this->check_node_test($this->nodes[$context]["parent"], $axis["node-test"]) ) {
			$nodes[] = $this->nodes[$context]["parent"];
		}
		return $nodes;
	}

	// Handles the XPath attribute axis.
	function handle_axis_attribute ( $axis, $context ) {
		$nodes = array();
		if ( $axis["node-test"] == "*" ) {
			if ( count($this->nodes[$context]["attributes"]) > 0 ) {
				foreach ( $this->nodes[$context]["attributes"] as $key => $value ) {
					$nodes[] = $context."/attribute::".$key;
				}
			}
		} elseif ( !empty($this->nodes[$context]["attributes"][$axis["node-test"]]) ) {
			$nodes[] = $context."/attribute::".$axis["node-test"];
		}
		return $nodes;
	}

	// Handles the XPath self axis.
	function handle_axis_self ( $axis, $context ) {
		$nodes = array();
		if ( $this->check_node_test($context, $axis["node-test"]) ) {
			$nodes[] = $context;
		}
		return $nodes;
	}


	function handle_axis_descendant ( $axis, $context ) {								// Handles the XPath descendant axis.
		$nodes = array();
		if (isset($this->nodes[$context]["children"])) {
			if ( count($this->nodes[$context]["children"]) > 0 ) {
				$children = $this->nodes[$context]["children"];
				foreach ( $children as $child_name => $child_position ) {
					for ( $i = 1; $i <= $child_position; $i++ ) {
						$child = $context."/".$child_name."[".$i."]";
						if ( $this->check_node_test($child, $axis["node-test"]) ) {
							$nodes[] = $child;
						}
						$nodes = array_merge($nodes, $this->handle_axis_descendant($axis, $child));
					}
				}
			}
		}
		return $nodes;
	}

	// Handles the XPath ancestor axis.
	function handle_axis_ancestor ( $axis, $context ) {
		$nodes = array();
		$parent = $this->nodes[$context]["parent"];
		if ( !empty($parent) ) {
			if ( $this->check_node_test($parent, $axis["node-test"]) ) {
				$nodes[] = $parent;
			}
			$nodes = array_merge($nodes,
			$this->handle_axis_ancestor($axis, $parent));
		}
	return $nodes;
	}

	// Handles the XPath namespace axis.
	function handle_axis_namespace ( $axis, $context ) {
		$nodes = array();
		if ( !empty($this->nodes[$context]["namespace"]) ) {
			$nodes[] = $context;
		}
		return $nodes;
	}

	// Handles the XPath following axis.
	function handle_axis_following ( $axis, $context ) {
		$nodes = array();
		$position = $this->nodes[$context]["document_position"];
		$found = false;
		foreach ( $this->nodes as $node => $data ) {
			if ( $found ) {
				if ( $this->nodes[$node]["document_position"] == $position ) {
					if ( $this->check_node_test($node, $axis["node-test"]) ) {
						$nodes[] = $node;
					}
				}
			}
			if ( $node == $context ) {
				$found = true;
			}
		}
		return $nodes;
	}

	// Handles the XPath preceding axis.
	function handle_axis_preceding ( $axis, $context ) {
		$nodes = array();
		$position = $this->nodes[$context]["document_position"];
		$found = true;
		foreach ( $this->nodes as $node => $data ) {
			if ( $node == $context ) {
				$found = false;
			}
			if ( $found ) {
				if ( $this->nodes[$node]["document_position"] == $position ) {
					if ( $this->check_node_test($node, $axis["node-test"]) ) {
						$nodes[] = $node;
					}
				}
			}
		}
		return $nodes;
	}

	// Handles the XPath following-sibling axis.
	function handle_axis_following_sibling ( $axis, $context ) {
		$nodes = array();
		$siblings = $this->handle_axis_child($axis, $this->nodes[$context]["parent"]);
		$found = false;
		foreach ( $siblings as $sibling ) {
			if ( $found ) {
				if ( $this->nodes[$sibling]["name"] == $this->nodes[$context]["name"] ) {
					if ( $this->check_node_test($sibling, $axis["node-test"]) ) {
						$nodes[] = $sibling;
					}
				}
			}
			if ( $sibling == $context ) {
				$found = true;
			}
		}
		return $nodes;
	}

	// Handles the XPath preceding-sibling axis.
	function handle_axis_preceding_sibling ( $axis, $context ) {
		$nodes = array();
		$siblings = $this->handle_axis_child($axis, $this->nodes[$context]["parent"]);
		$found = true;
		foreach ( $siblings as $sibling ) {
			if ( $sibling == $context ) {
				$found = false;
			}
			if ( $found ) {
				if ( $this->nodes[$sibling]["name"] == $this->nodes[$context]["name"] ) {
					if ( $this->check_node_test($sibling, $axis["node-test"]) ) {
						$nodes[] = $sibling;
					}
				}
			}
		}
		return $nodes;
	}

	//* Handles the XPath descendant-or-self axis.
	function handle_axis_descendant_or_self ( $axis, $context ) {
		$nodes = array();
		$nodes = array_merge($this->handle_axis_descendant($axis, $context), $this->handle_axis_self($axis, $context));
		return $nodes;
	}

	// Handles the XPath ancestor-or-self axis.
	function handle_axis_ancestor_or_self ( $axis, $context ) {
		$nodes = array();
		$nodes = array_merge($this->handle_axis_ancestor($axis, $context), $this->handle_axis_self($axis, $context));
		return $nodes;
	}

	// Handles the XPath function last.
	function handle_function_last ( $node, $arguments ) {
		$parent   = $this->nodes[$node]["parent"];
		$children = $this->nodes[$parent]["children"];
		$context  = $children[$this->nodes[$node]["name"]];
		return $context;
	}

	// Handles the XPath function position.
	function handle_function_position ( $node, $arguments ) {
		return $this->nodes[$node]["context-position"];
	}

	// Handles the XPath function count.
	function handle_function_count ( $node, $arguments ) {
		return count($this->evaluate($arguments, $node));
	}

	// Handles the XPath function id.
	function handle_function_id ( $node, $arguments ) {
		$arguments = trim($arguments);
		$arguments = explode(" ", $arguments);
		$nodes = array();
		foreach ( $this->nodes as $node => $position ) {
			if ( in_array($this->nodes[$node]["attributes"]["id"], $arguments) ) {
				$nodes[] = $node;
			}
		}
		return $nodes;
	}

	// Handles the XPath function name.
	function handle_function_name ( $node, $arguments ) {
		return $this->nodes[$node]["name"];
	}

	// Handles the XPath function string.
	function handle_function_string ( $node, $arguments ) {
		if ( ereg("^[0-9]+(\.[0-9]+)?$", $arguments) || ereg("^\.[0-9]+$", $arguments) ) {
			$number = doubleval($arguments);
			return strval($number);
		} elseif ( is_bool($arguments) ) {
			if ( $arguments == true ) {
				return "true";
			} else {
				return "false";
			}
		} elseif ( !empty($arguments) ) {
			$result = $this->evaluate($arguments, $node);
			$result = explode("|", implode("|", $result));
			return $result[0];
		} elseif ( empty($arguments) ) {
			return $node;
		} else {
			return "";
		}
	}

	// Handles the XPath function concat.
	function handle_function_concat ( $node, $arguments ) {
		$arguments = explode(",", $arguments);
		for ( $i = 0; $i < sizeof($arguments); $i++ ) {
			$arguments[$i] = trim($arguments[$i]);
			$arguments[$i] = $this->evaluate_predicate($node, $arguments[$i]);
		}
		$arguments = implode("", $arguments);
		return $arguments;
	}

	// Handles the XPath function starts-with.
	function handle_function_starts_with ( $node, $arguments ) {
		$first  = trim($this->prestr($arguments, ","));
		$second = trim($this->afterstr($arguments, ","));
		$first  = $this->evaluate_predicate($node, $first);
		$second = $this->evaluate_predicate($node, $second);
		if ( ereg("^".$second, $first) ) {
			return true;
		} else {
			return false;
		}
	}

	// Handles the XPath function contains.
	function handle_function_contains ( $node, $arguments ) {
		$first  = trim($this->prestr($arguments, ","));
		$second = trim($this->afterstr($arguments, ","));
		$first  = $this->evaluate_predicate($node, $first);
		$second = $this->evaluate_predicate($node, $second);
		if ( ereg($second, $first) ) {
			return true;
		} else {
			return false;
		}
	}

	// Handles the XPath function substring-before.
	function handle_function_substring_before ( $node, $arguments ) {
		$first  = trim($this->prestr($arguments, ","));
		$second = trim($this->afterstr($arguments, ","));
		$first  = $this->evaluate_predicate($node, $first);
		$second = $this->evaluate_predicate($node, $second);
		return $this->prestr(strval($first), strval($second));
	}

	// Handles the XPath function substring-after.
	function handle_function_substring_after ( $node, $arguments ) {
		$first  = trim($this->prestr($arguments, ","));
		$second = trim($this->afterstr($arguments, ","));
		$first  = $this->evaluate_predicate($node, $first);
		$second = $this->evaluate_predicate($node, $second);
		return $this->afterstr(strval($first), strval($second));
	}

	// Handles the XPath function substring.
	function handle_function_substring ( $node, $arguments ) {
		$arguments = explode(",", $arguments);
		for ( $i = 0; $i < sizeof($arguments); $i++ ) {
			$arguments[$i] = trim($arguments[$i]);
			$arguments[$i] = $this->evaluate_predicate($node, $arguments[$i]);
		}
		if ( !empty($arguments[2]) ) {
			return substr(strval($arguments[0]), $arguments[1] - 1, $arguments[2]);
		} else {
			return substr(strval($arguments[0]), $arguments[1] - 1);
		}
	}

	// Handles the XPath function string-length.
	function handle_function_string_length ( $node, $arguments ) {
		$arguments = trim($arguments);
		$arguments = $this->evaluate_predicate($node, $arguments);
		return strlen(strval($arguments));
	}

	// Handles the XPath function translate.
	function handle_function_translate ( $node, $arguments ) {
		$arguments = explode(",", $arguments);
		for ( $i = 0; $i < sizeof($arguments); $i++ ) {
			$arguments[$i] = trim($arguments[$i]);
			$arguments[$i] = $this->evaluate_predicate($node, $arguments[$i]);
		}
		return strtr($arguments[0], $arguments[1], $arguments[2]);
	}

	// Handles the XPath function boolean.
	function handle_function_boolean ( $node, $arguments ) {
		$arguments = trim($arguments);
		if ( ereg("^[0-9]+(\.[0-9]+)?$", $arguments) || ereg("^\.[0-9]+$", $arguments) ) {
			$number = doubleval($arguments);
			if ( $number == 0 ) {
				return false;
			} else {
				return true;
			}
		} elseif ( empty($arguments) ) {
			return false;
		} else {
			$result = $this->evaluate($arguments, $node);
			if ( count($result) > 0 ) {
				return true;
			} else {
				return false;
			}
		}
	}

	// Handles the XPath function not.
	function handle_function_not ( $node, $arguments ) {
		$arguments = trim($arguments);
		return !$this->evaluate_predicate($node, $arguments);
	}

	// Handles the XPath function true.
	function handle_function_true ( $node, $arguments ) {
		return true;
	}

	// Handles the XPath function false.
	function handle_function_false ( $node, $arguments ) {
		return false;
	}

	// Handles the XPath function lang.
	function handle_function_lang ( $node, $arguments ) {
		$arguments = trim($arguments);
		if ( empty($this->nodes[$node]["attributes"]["xml:lang"]) ) {
			while ( !empty($node) ) {
				$node = $this->nodes[$node]["parent"];
				if ( !empty($this->nodes[$node]["attributes"]["xml:lang"]) ) {
					if ( eregi("^".$arguments, $this->nodes[$node]["attributes"]["xml:lang"]) ) {
						return true;
					} else {
						return false;
					}
				}
			}
			return false;
		} else {
			if ( eregi("^".$arguments, $this->nodes[$node]["attributes"]["xml:lang"]) ) {
				return true;
			} else {
				return false;
			}
		}
	}

	// Handles the XPath function number.
	function handle_function_number ( $node, $arguments ) {
		if ( ereg("^[0-9]+(\.[0-9]+)?$", $arguments) || ereg("^\.[0-9]+$", $arguments) ) {
			return doubleval($arguments);
		} elseif ( is_bool($arguments) ) {
			if ( $arguments == true ) {
				return 1;
			} else {
				return 0;
			}
		}
	}

	// Handles the XPath function sum.
	function handle_function_sum ( $node, $arguments ) {
		$arguments = trim($arguments);
		$results = $this->evaluate($arguments, $node);
		$sum = 0;
		foreach ( $results as $result ) {
			$result = $this->get_content($result);
			$sum += doubleval($result);
		}
		return $sum;
	}

	// Handles the XPath function floor.
	function handle_function_floor ( $node, $arguments ) {
		$arguments = trim($arguments);
		$arguments = doubleval($arguments);
		return floor($arguments);
	}

	// Handles the XPath function ceiling.
	function handle_function_ceiling ( $node, $arguments ) {
		$arguments = trim($arguments);
		$arguments = doubleval($arguments);
		return ceil($arguments);
	}

	// Handles the XPath function round.
	function handle_function_round ( $node, $arguments ) {
		$arguments = trim($arguments);
		$arguments = doubleval($arguments);
		return round($arguments);
	}

	// Handles the XPath function text.
	function handle_function_text ( $node, $arguments ) {
		return $this->nodes[$node]["text"];
	}

	// Retrieves a substring before a delimiter.
	function prestr ( $string, $delimiter ) {
		return substr($string, 0, strlen($string) - strlen(strstr($string, "$delimiter")));
	}

	// Retrieves a substring after a delimiter.
	function afterstr ( $string, $delimiter ) {
		return substr($string, strpos($string, $delimiter) + strlen($delimiter));
	}

	// Displays an error message.
	function display_error ( $message ) {
		if ( func_num_args() > 1 ) {
			$arguments = func_get_args();
			$command = "\$message = sprintf(\$message, ";
			for ( $i = 1; $i < sizeof($arguments); $i++ ) {
				$command .= "\$arguments[".$i."], ";
			}
			$command = eregi_replace(", $", ");", $command);
			eval($command);
		}
		echo "<b>phpXML error:</b> ".$message;
		exit;
	}
}

?>