VERSION 5.00
Begin VB.Form frmBattle 
   Caption         =   "Battle v0.5 Alpha"
   ClientHeight    =   10995
   ClientLeft      =   8715
   ClientTop       =   3285
   ClientWidth     =   14400
   LinkTopic       =   "Form1"
   ScaleHeight     =   10995
   ScaleWidth      =   14400
   Begin VB.Frame frmSurStruct 
      Caption         =   "Surface Structures"
      Height          =   2415
      Left            =   3240
      TabIndex        =   175
      Top             =   8400
      Width           =   2655
      Begin VB.TextBox txtMonolith 
         Height          =   285
         Left            =   2040
         MaxLength       =   3
         TabIndex        =   219
         Text            =   "0"
         Top             =   2040
         Width           =   495
      End
      Begin VB.TextBox txtTurret 
         Height          =   285
         Left            =   2040
         MaxLength       =   3
         TabIndex        =   185
         Text            =   "0"
         Top             =   1680
         Width           =   495
      End
      Begin VB.TextBox txtIMPSDB 
         Height          =   285
         Left            =   2040
         MaxLength       =   3
         TabIndex        =   184
         Text            =   "0"
         Top             =   1320
         Width           =   495
      End
      Begin VB.TextBox txtSDB 
         Height          =   285
         Left            =   2040
         MaxLength       =   3
         TabIndex        =   183
         Text            =   "0"
         Top             =   960
         Width           =   495
      End
      Begin VB.TextBox txtIMPSSG 
         Height          =   285
         Left            =   2040
         MaxLength       =   3
         TabIndex        =   182
         Text            =   "0"
         Top             =   600
         Width           =   495
      End
      Begin VB.TextBox txtSSG 
         Height          =   285
         Left            =   2040
         MaxLength       =   3
         TabIndex        =   181
         Text            =   "0"
         Top             =   240
         Width           =   495
      End
      Begin VB.Label Label82 
         Caption         =   "Monolith"
         Height          =   255
         Left            =   120
         TabIndex        =   220
         Top             =   2040
         Width           =   1095
      End
      Begin VB.Label Label75 
         Caption         =   "Turret"
         Height          =   255
         Left            =   120
         TabIndex        =   180
         Top             =   1680
         Width           =   1095
      End
      Begin VB.Label Label74 
         Caption         =   "SDB Imp"
         Height          =   255
         Left            =   120
         TabIndex        =   179
         Top             =   1320
         Width           =   1095
      End
      Begin VB.Label Label73 
         Caption         =   "SDB"
         Height          =   255
         Left            =   120
         TabIndex        =   178
         Top             =   960
         Width           =   1095
      End
      Begin VB.Label Label72 
         Caption         =   "SSG Imp"
         Height          =   255
         Left            =   120
         TabIndex        =   177
         Top             =   600
         Width           =   1095
      End
      Begin VB.Label Label71 
         Caption         =   "SSG"
         Height          =   255
         Left            =   120
         TabIndex        =   176
         Top             =   240
         Width           =   1335
      End
   End
   Begin VB.ListBox lstDefender 
      Height          =   3180
      ItemData        =   "frmBattle.frx":0000
      Left            =   11760
      List            =   "frmBattle.frx":0002
      TabIndex        =   163
      Top             =   360
      Width           =   2415
   End
   Begin VB.ListBox lstAttacker 
      Height          =   3180
      ItemData        =   "frmBattle.frx":0004
      Left            =   9120
      List            =   "frmBattle.frx":0006
      MultiSelect     =   2  'Extended
      TabIndex        =   161
      Top             =   360
      Width           =   2415
   End
   Begin VB.Frame frmShips 
      Height          =   10935
      Left            =   0
      TabIndex        =   0
      Top             =   0
      Width           =   14295
      Begin VB.ComboBox cmbFormula 
         Height          =   315
         ItemData        =   "frmBattle.frx":0008
         Left            =   12960
         List            =   "frmBattle.frx":000A
         Style           =   2  'Dropdown List
         TabIndex        =   236
         Top             =   9840
         Width           =   1215
      End
      Begin VB.Frame frmDrone 
         Caption         =   "Drones"
         Height          =   615
         Left            =   3240
         TabIndex        =   233
         Top             =   7680
         Width           =   2655
         Begin VB.TextBox txtDrone 
            Height          =   285
            Left            =   2040
            MaxLength       =   4
            TabIndex        =   234
            Text            =   "0"
            Top             =   240
            Width           =   495
         End
         Begin VB.Label Label40 
            Caption         =   "Stinger Drone"
            Height          =   255
            Left            =   120
            TabIndex        =   235
            Top             =   240
            Width           =   1695
         End
      End
      Begin VB.CommandButton cmdClearAttacker 
         Caption         =   "Clear Attacker"
         Height          =   375
         Left            =   11040
         TabIndex        =   211
         Top             =   9840
         Width           =   1215
      End
      Begin VB.TextBox txtTestNumber 
         Height          =   375
         Left            =   12360
         TabIndex        =   210
         Text            =   "1"
         Top             =   9840
         Width           =   495
      End
      Begin VB.CommandButton cmdTest 
         Caption         =   "Test"
         Height          =   375
         Left            =   12960
         TabIndex        =   209
         Top             =   9000
         Width           =   1215
      End
      Begin VB.TextBox txtResult 
         Height          =   2655
         Left            =   9120
         MultiLine       =   -1  'True
         ScrollBars      =   2  'Vertical
         TabIndex        =   207
         Top             =   3600
         Width           =   5055
      End
      Begin VB.CommandButton cmdFight 
         Caption         =   "Fight"
         Default         =   -1  'True
         Height          =   375
         Left            =   12960
         TabIndex        =   206
         Top             =   10320
         Width           =   1215
      End
      Begin VB.Frame frmRatings 
         Caption         =   "Ratings"
         Height          =   1815
         Left            =   6000
         TabIndex        =   192
         Top             =   8400
         Width           =   2655
         Begin VB.TextBox txtVolleys 
            Height          =   285
            Left            =   1440
            MaxLength       =   3
            TabIndex        =   199
            Text            =   "5"
            Top             =   1440
            Width           =   495
         End
         Begin VB.TextBox txtDefenderDurability 
            Height          =   285
            Left            =   2040
            MaxLength       =   2
            TabIndex        =   198
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtDefenderDefense 
            Height          =   285
            Left            =   2040
            MaxLength       =   2
            TabIndex        =   197
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.TextBox txtAttackerDurability 
            Height          =   285
            Left            =   1440
            MaxLength       =   2
            TabIndex        =   196
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtAttackerDefense 
            Height          =   285
            Left            =   1440
            MaxLength       =   2
            TabIndex        =   195
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.TextBox txtDefenderOffense 
            Height          =   285
            Left            =   2040
            MaxLength       =   2
            TabIndex        =   194
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.TextBox txtAttackerOffense 
            Height          =   285
            Left            =   1440
            MaxLength       =   2
            TabIndex        =   193
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.Label Label50 
            Caption         =   "Def"
            Height          =   255
            Left            =   2040
            TabIndex        =   232
            Top             =   120
            Width           =   495
         End
         Begin VB.Label Label51 
            Caption         =   "Att"
            Height          =   255
            Left            =   1440
            TabIndex        =   231
            Top             =   120
            Width           =   495
         End
         Begin VB.Label lblVolleys 
            Caption         =   "Volleys"
            Height          =   255
            Left            =   120
            TabIndex        =   203
            Top             =   1440
            Width           =   495
         End
         Begin VB.Label Label55 
            Caption         =   "Durability"
            Height          =   255
            Left            =   120
            TabIndex        =   202
            Top             =   1080
            Width           =   1335
         End
         Begin VB.Label Label54 
            Caption         =   "Defense Rating"
            Height          =   255
            Left            =   120
            TabIndex        =   201
            Top             =   720
            Width           =   1335
         End
         Begin VB.Label Label52 
            Caption         =   "Offense Rating"
            Height          =   255
            Left            =   120
            TabIndex        =   200
            Top             =   360
            Width           =   1095
         End
      End
      Begin VB.ListBox lstResult 
         Height          =   2010
         ItemData        =   "frmBattle.frx":000C
         Left            =   6360
         List            =   "frmBattle.frx":000E
         MultiSelect     =   2  'Extended
         TabIndex        =   191
         Top             =   6360
         Width           =   7815
      End
      Begin VB.Frame frmDefenseFormation 
         Caption         =   "Defense Formation"
         Height          =   1335
         Left            =   11040
         TabIndex        =   165
         Top             =   8400
         Width           =   1815
         Begin VB.OptionButton optAmbush 
            Caption         =   "Ambush"
            Height          =   255
            Left            =   240
            TabIndex        =   170
            Top             =   960
            Width           =   1095
         End
         Begin VB.OptionButton optFighterSpread 
            Caption         =   "Fighter Spread"
            Height          =   255
            Left            =   240
            TabIndex        =   169
            Top             =   600
            Width           =   1335
         End
         Begin VB.OptionButton optStandard 
            Caption         =   "Standard"
            Height          =   255
            Left            =   240
            TabIndex        =   168
            Top             =   240
            Value           =   -1  'True
            Width           =   1335
         End
      End
      Begin VB.Frame frmAttackFormation 
         Caption         =   "Attack Formation"
         Height          =   2415
         Left            =   8760
         TabIndex        =   164
         Top             =   8400
         Width           =   2175
         Begin VB.CheckBox chkRandom 
            Caption         =   "Random"
            Height          =   255
            Left            =   1080
            TabIndex        =   237
            Top             =   2040
            Width           =   975
         End
         Begin VB.OptionButton optStructureRecon 
            Caption         =   "Structure recon"
            Height          =   255
            Left            =   120
            TabIndex        =   218
            Top             =   1680
            Width           =   1455
         End
         Begin VB.OptionButton optSensorBlind 
            Caption         =   "Sensor Blind"
            Height          =   255
            Left            =   120
            TabIndex        =   213
            Top             =   960
            Width           =   1335
         End
         Begin VB.OptionButton optFleetRecon 
            Caption         =   "Fleet recon"
            Height          =   255
            Left            =   120
            TabIndex        =   212
            Top             =   1320
            Width           =   1335
         End
         Begin VB.OptionButton optBombard 
            Caption         =   "Bombard"
            Height          =   255
            Left            =   120
            TabIndex        =   208
            Top             =   2040
            Width           =   975
         End
         Begin VB.OptionButton optFighterScreen 
            Caption         =   "Fighter Screen"
            Height          =   255
            Left            =   120
            TabIndex        =   167
            Top             =   600
            Width           =   1335
         End
         Begin VB.OptionButton optNormal 
            Caption         =   "Normal"
            Height          =   255
            Left            =   120
            TabIndex        =   166
            Top             =   240
            Value           =   -1  'True
            Width           =   1215
         End
      End
      Begin VB.CommandButton cmdSort 
         Caption         =   "Sort"
         Height          =   375
         Left            =   12960
         TabIndex        =   162
         Top             =   8520
         Width           =   1215
      End
      Begin VB.Frame frmStructures 
         Caption         =   "Orbital Structures"
         Height          =   6135
         Left            =   6360
         TabIndex        =   136
         Top             =   120
         Width           =   2655
         Begin VB.TextBox txtImpSat 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   217
            Text            =   "0"
            Top             =   5760
            Width           =   495
         End
         Begin VB.TextBox txtSat 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   216
            Text            =   "0"
            Top             =   5400
            Width           =   495
         End
         Begin VB.TextBox txtIMPOMDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   189
            Text            =   "0"
            Top             =   5040
            Width           =   495
         End
         Begin VB.TextBox txtOMDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   187
            Text            =   "0"
            Top             =   4680
            Width           =   495
         End
         Begin VB.TextBox txtOBDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   159
            Text            =   "0"
            Top             =   4320
            Width           =   495
         End
         Begin VB.TextBox txtIMPOCY 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   158
            Text            =   "0"
            Top             =   3960
            Width           =   495
         End
         Begin VB.TextBox txtOCY 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   157
            Text            =   "0"
            Top             =   3600
            Width           =   495
         End
         Begin VB.TextBox txtIMPOSDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   156
            Text            =   "0"
            Top             =   3240
            Width           =   495
         End
         Begin VB.TextBox txtOSDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   155
            Text            =   "0"
            Top             =   2880
            Width           =   495
         End
         Begin VB.TextBox txtIMPODPDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   154
            Text            =   "0"
            Top             =   2520
            Width           =   495
         End
         Begin VB.TextBox txtODPDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   153
            Text            =   "0"
            Top             =   2160
            Width           =   495
         End
         Begin VB.TextBox txtODMDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   152
            Text            =   "0"
            Top             =   1800
            Width           =   495
         End
         Begin VB.TextBox txtStarbaseDEF 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   151
            Text            =   "0"
            Top             =   1440
            Width           =   495
         End
         Begin VB.TextBox txtFolder 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   150
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtImpJumpgate 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   149
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.TextBox txtJumpgate 
            Height          =   285
            Left            =   2040
            MaxLength       =   3
            TabIndex        =   148
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.Label Label81 
            Caption         =   "Satellites (Imp)"
            Height          =   255
            Left            =   120
            TabIndex        =   215
            Top             =   5760
            Width           =   1815
         End
         Begin VB.Label Label80 
            Caption         =   "Satellites"
            Height          =   255
            Left            =   120
            TabIndex        =   214
            Top             =   5400
            Width           =   1815
         End
         Begin VB.Label Label77 
            Caption         =   "Orbital Minefield (Imp)"
            Height          =   255
            Left            =   120
            TabIndex        =   188
            Top             =   5040
            Width           =   1815
         End
         Begin VB.Label Label76 
            Caption         =   "Orbital Minefield"
            Height          =   255
            Left            =   120
            TabIndex        =   186
            Top             =   4680
            Width           =   1815
         End
         Begin VB.Label Label66 
            Caption         =   "Orbital Bulwark"
            Height          =   255
            Left            =   120
            TabIndex        =   160
            Top             =   4320
            Width           =   1815
         End
         Begin VB.Label Label65 
            Caption         =   "OCY (Imp)"
            Height          =   255
            Left            =   120
            TabIndex        =   147
            Top             =   3960
            Width           =   1815
         End
         Begin VB.Label Label64 
            Caption         =   "OCY"
            Height          =   255
            Left            =   120
            TabIndex        =   146
            Top             =   3600
            Width           =   1815
         End
         Begin VB.Label Label63 
            Caption         =   "Orbital Shield (Imp)"
            Height          =   255
            Left            =   120
            TabIndex        =   145
            Top             =   3240
            Width           =   2295
         End
         Begin VB.Label Label62 
            Caption         =   "Orbital Shield"
            Height          =   255
            Left            =   120
            TabIndex        =   144
            Top             =   2880
            Width           =   2295
         End
         Begin VB.Label Label61 
            Caption         =   "Orbital Def Platform (Imp)"
            Height          =   255
            Left            =   120
            TabIndex        =   143
            Top             =   2520
            Width           =   2295
         End
         Begin VB.Label Label60 
            Caption         =   "Orbital Defense Platform"
            Height          =   255
            Left            =   120
            TabIndex        =   142
            Top             =   2160
            Width           =   1935
         End
         Begin VB.Label Label59 
            Caption         =   "Improved Jumpgate"
            Height          =   255
            Left            =   120
            TabIndex        =   141
            Top             =   720
            Width           =   1575
         End
         Begin VB.Label Label58 
            Caption         =   "Space Folder"
            Height          =   255
            Left            =   120
            TabIndex        =   140
            Top             =   1080
            Width           =   1095
         End
         Begin VB.Label Label57 
            Caption         =   "Starbase"
            Height          =   255
            Left            =   120
            TabIndex        =   139
            Top             =   1440
            Width           =   1095
         End
         Begin VB.Label Label56 
            Caption         =   "Orbital Defense Monitor"
            Height          =   255
            Left            =   120
            TabIndex        =   138
            Top             =   1800
            Width           =   1935
         End
         Begin VB.Label Label53 
            Caption         =   "Jumpgate"
            Height          =   255
            Left            =   120
            TabIndex        =   137
            Top             =   360
            Width           =   1095
         End
      End
      Begin VB.CommandButton cmdClearDefender 
         Caption         =   "Clear Defender"
         Height          =   375
         Left            =   11040
         TabIndex        =   135
         Top             =   10320
         Width           =   1215
      End
      Begin VB.Frame FrmSpecial 
         Caption         =   "Special Ships"
         Height          =   10455
         Left            =   120
         TabIndex        =   22
         Top             =   120
         Width           =   3015
         Begin VB.TextBox txtMaelstromDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   224
            Text            =   "0"
            Top             =   6480
            Width           =   495
         End
         Begin VB.TextBox txtSandstormDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   223
            Text            =   "0"
            Top             =   7920
            Width           =   495
         End
         Begin VB.TextBox txtMaelstrom 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   222
            Text            =   "0"
            Top             =   6480
            Width           =   495
         End
         Begin VB.TextBox txtSandstorm 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   221
            Text            =   "0"
            Top             =   7920
            Width           =   495
         End
         Begin VB.TextBox txtTortoiseDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   173
            Text            =   "0"
            Top             =   9000
            Width           =   495
         End
         Begin VB.TextBox txtTortoise 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   172
            Text            =   "0"
            Top             =   9000
            Width           =   495
         End
         Begin VB.TextBox txtZephyerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   134
            Text            =   "0"
            Top             =   10080
            Width           =   495
         End
         Begin VB.TextBox txtWayfarerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   133
            Text            =   "0"
            Top             =   9720
            Width           =   495
         End
         Begin VB.TextBox txtVespaDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   132
            Text            =   "0"
            Top             =   9360
            Width           =   495
         End
         Begin VB.TextBox txtTerrapinDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   131
            Text            =   "0"
            Top             =   8640
            Width           =   495
         End
         Begin VB.TextBox txtTanglerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   130
            Text            =   "0"
            Top             =   8280
            Width           =   495
         End
         Begin VB.TextBox txtRavenDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   129
            Text            =   "0"
            Top             =   7560
            Width           =   495
         End
         Begin VB.TextBox txtPrivateerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   128
            Text            =   "0"
            Top             =   7200
            Width           =   495
         End
         Begin VB.TextBox txtOrcaDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   127
            Text            =   "0"
            Top             =   6840
            Width           =   495
         End
         Begin VB.TextBox txtLeopardDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   126
            Text            =   "0"
            Top             =   6120
            Width           =   495
         End
         Begin VB.TextBox txtJudicatorDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   125
            Text            =   "0"
            Top             =   5760
            Width           =   495
         End
         Begin VB.TextBox txtInterdictorDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   124
            Text            =   "0"
            Top             =   5400
            Width           =   495
         End
         Begin VB.TextBox txtHurricaneDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   123
            Text            =   "0"
            Top             =   5040
            Width           =   495
         End
         Begin VB.TextBox txtHammerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   122
            Text            =   "0"
            Top             =   4680
            Width           =   495
         End
         Begin VB.TextBox txtGoliathDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   121
            Text            =   "0"
            Top             =   4320
            Width           =   495
         End
         Begin VB.TextBox txtDragonDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   120
            Text            =   "0"
            Top             =   3960
            Width           =   495
         End
         Begin VB.TextBox txtCrusaderDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   119
            Text            =   "0"
            Top             =   3600
            Width           =   495
         End
         Begin VB.TextBox txtColossusDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   118
            Text            =   "0"
            Top             =   3240
            Width           =   495
         End
         Begin VB.TextBox txtCollectorDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   117
            Text            =   "0"
            Top             =   2880
            Width           =   495
         End
         Begin VB.TextBox txtBerzerkerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   116
            Text            =   "0"
            Top             =   2520
            Width           =   495
         End
         Begin VB.TextBox txtBarracudaDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   115
            Text            =   "0"
            Top             =   2160
            Width           =   495
         End
         Begin VB.TextBox txtBadgerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   114
            Text            =   "0"
            Top             =   1800
            Width           =   495
         End
         Begin VB.TextBox txtAvalancheDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   113
            Text            =   "0"
            Top             =   1440
            Width           =   495
         End
         Begin VB.TextBox txtAspDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   112
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtAnvilDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   111
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.TextBox txtAegisDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   110
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.TextBox txtZephyer 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   109
            Text            =   "0"
            Top             =   10080
            Width           =   495
         End
         Begin VB.TextBox txtWayfarer 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   108
            Text            =   "0"
            Top             =   9720
            Width           =   495
         End
         Begin VB.TextBox txtVespa 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   107
            Text            =   "0"
            Top             =   9360
            Width           =   495
         End
         Begin VB.TextBox txtTerrapin 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   106
            Text            =   "0"
            Top             =   8640
            Width           =   495
         End
         Begin VB.TextBox txtTangler 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   105
            Text            =   "0"
            Top             =   8280
            Width           =   495
         End
         Begin VB.TextBox txtRaven 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   104
            Text            =   "0"
            Top             =   7560
            Width           =   495
         End
         Begin VB.TextBox txtPrivateer 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   103
            Text            =   "0"
            Top             =   7200
            Width           =   495
         End
         Begin VB.TextBox txtOrca 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   102
            Text            =   "0"
            Top             =   6840
            Width           =   495
         End
         Begin VB.TextBox txtLeopard 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   101
            Text            =   "0"
            Top             =   6120
            Width           =   495
         End
         Begin VB.TextBox txtJudicator 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   100
            Text            =   "0"
            Top             =   5760
            Width           =   495
         End
         Begin VB.TextBox txtInterdictor 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   99
            Text            =   "0"
            Top             =   5400
            Width           =   495
         End
         Begin VB.TextBox txtHurricane 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   98
            Text            =   "0"
            Top             =   5040
            Width           =   495
         End
         Begin VB.TextBox txtHammer 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   97
            Text            =   "0"
            Top             =   4680
            Width           =   495
         End
         Begin VB.TextBox txtGoliath 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   96
            Text            =   "0"
            Top             =   4320
            Width           =   495
         End
         Begin VB.TextBox txtDragon 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   95
            Text            =   "0"
            Top             =   3960
            Width           =   495
         End
         Begin VB.TextBox txtCrusader 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   94
            Text            =   "0"
            Top             =   3600
            Width           =   495
         End
         Begin VB.TextBox txtColossus 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   93
            Text            =   "0"
            Top             =   3240
            Width           =   495
         End
         Begin VB.TextBox txtCollector 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   92
            Text            =   "0"
            Top             =   2880
            Width           =   495
         End
         Begin VB.TextBox txtBerzerker 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   91
            Text            =   "0"
            Top             =   2520
            Width           =   495
         End
         Begin VB.TextBox txtBarracuda 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   90
            Text            =   "0"
            Top             =   2160
            Width           =   495
         End
         Begin VB.TextBox txtBadger 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   89
            Text            =   "0"
            Top             =   1800
            Width           =   495
         End
         Begin VB.TextBox txtAvalanche 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   88
            Text            =   "0"
            Top             =   1440
            Width           =   495
         End
         Begin VB.TextBox txtAsp 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   87
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtAnvil 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   86
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.TextBox txtAegis 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   85
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.Label Label48 
            Caption         =   "Def"
            Height          =   255
            Left            =   2520
            TabIndex        =   228
            Top             =   120
            Width           =   375
         End
         Begin VB.Label Label49 
            Caption         =   "Att"
            Height          =   255
            Left            =   1920
            TabIndex        =   227
            Top             =   120
            Width           =   375
         End
         Begin VB.Label Label84 
            Caption         =   "Maelstrom"
            Height          =   255
            Left            =   120
            TabIndex        =   226
            Top             =   6480
            Width           =   1695
         End
         Begin VB.Label Label83 
            Caption         =   "Sandstorm"
            Height          =   255
            Left            =   120
            TabIndex        =   225
            Top             =   7920
            Width           =   1695
         End
         Begin VB.Label Label67 
            Caption         =   "Tortoise Battleship"
            Height          =   255
            Left            =   120
            TabIndex        =   171
            Top             =   9000
            Width           =   1695
         End
         Begin VB.Label Label47 
            Caption         =   "Wayfarer"
            Height          =   255
            Left            =   120
            TabIndex        =   84
            Top             =   9720
            Width           =   1695
         End
         Begin VB.Label Label46 
            Caption         =   "Zephyr Fast Destroyer"
            Height          =   255
            Left            =   120
            TabIndex        =   83
            Top             =   10080
            Width           =   1695
         End
         Begin VB.Label Label45 
            Caption         =   "Tangler Defense Barge"
            Height          =   255
            Left            =   120
            TabIndex        =   82
            Top             =   8280
            Width           =   1695
         End
         Begin VB.Label Label44 
            Caption         =   "Terrapin Carrier"
            Height          =   255
            Left            =   120
            TabIndex        =   81
            Top             =   8640
            Width           =   1695
         End
         Begin VB.Label Label43 
            Caption         =   "Vespa Siege Carrier"
            Height          =   255
            Left            =   120
            TabIndex        =   80
            Top             =   9360
            Width           =   1695
         End
         Begin VB.Label Label42 
            Caption         =   "Privateer"
            Height          =   255
            Left            =   120
            TabIndex        =   79
            Top             =   7200
            Width           =   1695
         End
         Begin VB.Label Label41 
            Caption         =   "Raven"
            Height          =   255
            Left            =   120
            TabIndex        =   78
            Top             =   7560
            Width           =   1695
         End
         Begin VB.Label Label39 
            Caption         =   "Hurricane"
            Height          =   255
            Left            =   120
            TabIndex        =   77
            Top             =   5040
            Width           =   1695
         End
         Begin VB.Label Label38 
            Caption         =   "Interdictor"
            Height          =   255
            Left            =   120
            TabIndex        =   76
            Top             =   5400
            Width           =   1695
         End
         Begin VB.Label Label37 
            Caption         =   "Judicator"
            Height          =   255
            Left            =   120
            TabIndex        =   75
            Top             =   5760
            Width           =   1695
         End
         Begin VB.Label Label36 
            Caption         =   "Leopard"
            Height          =   255
            Left            =   120
            TabIndex        =   74
            Top             =   6120
            Width           =   1695
         End
         Begin VB.Label Label35 
            Caption         =   "Orca"
            Height          =   255
            Left            =   120
            TabIndex        =   73
            Top             =   6840
            Width           =   1695
         End
         Begin VB.Label Label34 
            Caption         =   "Goliath"
            Height          =   255
            Left            =   120
            TabIndex        =   72
            Top             =   4320
            Width           =   1695
         End
         Begin VB.Label Label33 
            Caption         =   "Dragon Mobile Assault"
            Height          =   255
            Left            =   120
            TabIndex        =   71
            Top             =   3960
            Width           =   1695
         End
         Begin VB.Label Label32 
            Caption         =   "Crusader"
            Height          =   255
            Left            =   120
            TabIndex        =   70
            Top             =   3600
            Width           =   1695
         End
         Begin VB.Label Label31 
            Caption         =   "Avalanche"
            Height          =   255
            Left            =   120
            TabIndex        =   69
            Top             =   1440
            Width           =   1695
         End
         Begin VB.Label Label30 
            Caption         =   "Badger"
            Height          =   255
            Left            =   120
            TabIndex        =   68
            Top             =   1800
            Width           =   1695
         End
         Begin VB.Label Label29 
            Caption         =   "Barracuda"
            Height          =   255
            Left            =   120
            TabIndex        =   67
            Top             =   2160
            Width           =   1695
         End
         Begin VB.Label Label28 
            Caption         =   "Berzerker"
            Height          =   255
            Left            =   120
            TabIndex        =   66
            Top             =   2520
            Width           =   1695
         End
         Begin VB.Label Label2 
            Caption         =   "Collector"
            Height          =   255
            Left            =   120
            TabIndex        =   65
            Top             =   2880
            Width           =   1695
         End
         Begin VB.Label Label1 
            Caption         =   "Hammer Gunship"
            Height          =   255
            Left            =   120
            TabIndex        =   64
            Top             =   4680
            Width           =   1695
         End
         Begin VB.Label Label17 
            Caption         =   "Colossus"
            Height          =   255
            Left            =   120
            TabIndex        =   26
            Top             =   3240
            Width           =   1695
         End
         Begin VB.Label Label8 
            Caption         =   "Asp Heavy Cruiser"
            Height          =   255
            Left            =   120
            TabIndex        =   25
            Top             =   1080
            Width           =   1695
         End
         Begin VB.Label Label11 
            Caption         =   "Anvil BattleShip"
            Height          =   255
            Left            =   120
            TabIndex        =   24
            Top             =   720
            Width           =   1695
         End
         Begin VB.Label Label22 
            Caption         =   "Aegis Mobile Shield"
            Height          =   255
            Left            =   120
            TabIndex        =   23
            Top             =   360
            Width           =   1695
         End
      End
      Begin VB.Frame FrmStandar 
         Caption         =   "Standard Ships"
         Height          =   4695
         Left            =   3240
         TabIndex        =   9
         Top             =   120
         Width           =   3015
         Begin VB.TextBox txtScout 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   190
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.TextBox txtHCDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   49
            Text            =   "0"
            Top             =   4320
            Width           =   495
         End
         Begin VB.TextBox txtDreadsDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   48
            Text            =   "0"
            Top             =   3960
            Width           =   495
         End
         Begin VB.TextBox txtBatsDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   47
            Text            =   "0"
            Top             =   3600
            Width           =   495
         End
         Begin VB.TextBox txtLCDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   46
            Text            =   "0"
            Top             =   3240
            Width           =   495
         End
         Begin VB.TextBox txtHCruiserDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   45
            Text            =   "0"
            Top             =   2880
            Width           =   495
         End
         Begin VB.TextBox txtCruiserDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   44
            Text            =   "0"
            Top             =   2520
            Width           =   495
         End
         Begin VB.TextBox txtFSDDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   43
            Text            =   "0"
            Top             =   2160
            Width           =   495
         End
         Begin VB.TextBox txtDestroyerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   42
            Text            =   "0"
            Top             =   1800
            Width           =   495
         End
         Begin VB.TextBox txtImpFrigateDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   41
            Text            =   "0"
            Top             =   1440
            Width           =   495
         End
         Begin VB.TextBox txtFrigateDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   40
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtDRScoutDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   39
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.TextBox txtScoutDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   38
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.TextBox txtHC 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   37
            Text            =   "0"
            Top             =   4320
            Width           =   495
         End
         Begin VB.TextBox txtDreads 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   36
            Text            =   "0"
            Top             =   3960
            Width           =   495
         End
         Begin VB.TextBox txtBats 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   35
            Text            =   "0"
            Top             =   3600
            Width           =   495
         End
         Begin VB.TextBox txtLC 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   34
            Text            =   "0"
            Top             =   3240
            Width           =   495
         End
         Begin VB.TextBox txtHCruiser 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   33
            Text            =   "0"
            Top             =   2880
            Width           =   495
         End
         Begin VB.TextBox txtCruiser 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   32
            Text            =   "0"
            Top             =   2520
            Width           =   495
         End
         Begin VB.TextBox txtFSD 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   31
            Text            =   "0"
            Top             =   2160
            Width           =   495
         End
         Begin VB.TextBox txtDestroyer 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   30
            Text            =   "0"
            Top             =   1800
            Width           =   495
         End
         Begin VB.TextBox txtImpFrigate 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   29
            Text            =   "0"
            Top             =   1440
            Width           =   495
         End
         Begin VB.TextBox txtFrigate 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   28
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtDRScout 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   27
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.Label Label26 
            Caption         =   "Def"
            Height          =   255
            Left            =   2400
            TabIndex        =   230
            Top             =   120
            Width           =   495
         End
         Begin VB.Label Label27 
            Caption         =   "Att"
            Height          =   255
            Left            =   1800
            TabIndex        =   229
            Top             =   120
            Width           =   495
         End
         Begin VB.Label Label18 
            Caption         =   "Heavy Carrier"
            Height          =   255
            Left            =   120
            TabIndex        =   21
            Top             =   4320
            Width           =   1695
         End
         Begin VB.Label Label19 
            Caption         =   "Dreadnought"
            Height          =   255
            Left            =   120
            TabIndex        =   20
            Top             =   3960
            Width           =   1695
         End
         Begin VB.Label Label3 
            Caption         =   "Battleship"
            Height          =   255
            Left            =   120
            TabIndex        =   19
            Top             =   3600
            Width           =   975
         End
         Begin VB.Label Label20 
            Caption         =   "Light Carrier"
            Height          =   255
            Left            =   120
            TabIndex        =   18
            Top             =   3240
            Width           =   1695
         End
         Begin VB.Label Label12 
            Caption         =   "Heavy Cruiser"
            Height          =   255
            Left            =   120
            TabIndex        =   17
            Top             =   2880
            Width           =   1695
         End
         Begin VB.Label Label4 
            Caption         =   "Cruiser"
            Height          =   255
            Left            =   120
            TabIndex        =   16
            Top             =   2520
            Width           =   975
         End
         Begin VB.Label Label14 
            Caption         =   "Fire Support Destroyer"
            Height          =   255
            Left            =   120
            TabIndex        =   15
            Top             =   2160
            Width           =   1815
         End
         Begin VB.Label Label13 
            Caption         =   "Destroyer"
            Height          =   255
            Left            =   120
            TabIndex        =   14
            Top             =   1800
            Width           =   1695
         End
         Begin VB.Label Label15 
            Caption         =   "Improved Frigate"
            Height          =   255
            Left            =   120
            TabIndex        =   13
            Top             =   1440
            Width           =   1695
         End
         Begin VB.Label Label7 
            Caption         =   "Frigate"
            Height          =   255
            Left            =   120
            TabIndex        =   12
            Top             =   1080
            Width           =   1215
         End
         Begin VB.Label Label6 
            Caption         =   "Scout"
            Height          =   255
            Left            =   120
            TabIndex        =   11
            Top             =   360
            Width           =   1095
         End
         Begin VB.Label Label16 
            Caption         =   "Deep Recon Scout"
            Height          =   255
            Left            =   120
            TabIndex        =   10
            Top             =   720
            Width           =   1695
         End
      End
      Begin VB.Frame frmFighter 
         Caption         =   "Fighter"
         Height          =   2895
         Left            =   3240
         TabIndex        =   1
         Top             =   4800
         Width           =   3015
         Begin VB.TextBox txtDaggerDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   63
            Text            =   "0"
            Top             =   2520
            Width           =   495
         End
         Begin VB.TextBox txtWaspDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   62
            Text            =   "0"
            Top             =   2160
            Width           =   495
         End
         Begin VB.TextBox txtFangDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   61
            Text            =   "0"
            Top             =   1800
            Width           =   495
         End
         Begin VB.TextBox txtHBDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   60
            Text            =   "0"
            Top             =   1440
            Width           =   495
         End
         Begin VB.TextBox txtAIDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   59
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtFBDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   58
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.TextBox txtFIDEF 
            Height          =   285
            Left            =   2400
            MaxLength       =   4
            TabIndex        =   57
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.TextBox txtDagger 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   56
            Text            =   "0"
            Top             =   2520
            Width           =   495
         End
         Begin VB.TextBox txtWasp 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   55
            Text            =   "0"
            Top             =   2160
            Width           =   495
         End
         Begin VB.TextBox txtFang 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   54
            Text            =   "0"
            Top             =   1800
            Width           =   495
         End
         Begin VB.TextBox txtHB 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   53
            Text            =   "0"
            Top             =   1440
            Width           =   495
         End
         Begin VB.TextBox txtAI 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   52
            Text            =   "0"
            Top             =   1080
            Width           =   495
         End
         Begin VB.TextBox txtFB 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   51
            Text            =   "0"
            Top             =   720
            Width           =   495
         End
         Begin VB.TextBox txtFI 
            Height          =   285
            Left            =   1800
            MaxLength       =   4
            TabIndex        =   50
            Text            =   "0"
            Top             =   360
            Width           =   495
         End
         Begin VB.Label Label25 
            Caption         =   "Wasp Heavy Fighter"
            Height          =   255
            Left            =   120
            TabIndex        =   8
            Top             =   2160
            Width           =   1695
         End
         Begin VB.Label Label23 
            Caption         =   "Fang Heavy Bomber"
            Height          =   255
            Left            =   120
            TabIndex        =   7
            Top             =   1800
            Width           =   1695
         End
         Begin VB.Label Label24 
            Caption         =   "Dagger Heavy Fighter"
            Height          =   255
            Left            =   120
            TabIndex        =   6
            Top             =   2520
            Width           =   1695
         End
         Begin VB.Label Label10 
            Caption         =   "Heavy bomber"
            Height          =   255
            Left            =   120
            TabIndex        =   5
            Top             =   1440
            Width           =   1695
         End
         Begin VB.Label Label9 
            Caption         =   "Fighter Interceptor"
            Height          =   255
            Left            =   120
            TabIndex        =   4
            Top             =   360
            Width           =   1695
         End
         Begin VB.Label Label21 
            Caption         =   "Advanced Interceptor"
            Height          =   255
            Left            =   120
            TabIndex        =   3
            Top             =   1080
            Width           =   1695
         End
         Begin VB.Label Label5 
            Caption         =   "Fighter Bomber"
            Height          =   255
            Left            =   120
            TabIndex        =   2
            Top             =   720
            Width           =   1335
         End
      End
      Begin VB.Label Label79 
         Caption         =   "Defender"
         Height          =   255
         Left            =   11760
         TabIndex        =   205
         Top             =   120
         Width           =   855
      End
      Begin VB.Label Label78 
         Caption         =   "Attacker"
         Height          =   255
         Left            =   9120
         TabIndex        =   204
         Top             =   120
         Width           =   855
      End
   End
   Begin VB.Label Label70 
      Caption         =   "Result"
      Height          =   255
      Left            =   9480
      TabIndex        =   174
      Top             =   8160
      Width           =   855
   End
End
Attribute VB_Name = "frmBattle"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private colAttacker As colShipsAttacker
Private colDefender As colShipsDefender
Private intDefenderOffense As Integer
Private intDefenderDefense As Integer
Private intDefenderDurability As Integer
Private intAttackerOffense As Integer
Private intAttackerDefense As Integer
Private intAttackerDurability As Integer
Private DEV As Boolean

Private sngRandomfactor As Single
Private sngRandomDropOffRatio As Single
Private sngCriticalHitRatio As Single
Private strLogFile As String


Private Sub cmbFormula_LostFocus()
    WriteINIFile
End Sub

Private Sub cmdClearAttacker_Click()
    txtScout.Text = "0"
    txtDRScout.Text = "0"
    txtFrigate.Text = "0"
    txtImpFrigate.Text = "0"
    txtDestroyer.Text = "0"
    txtFSD.Text = "0"
    txtCruiser.Text = "0"
    txtHCruiser.Text = "0"
    txtLC.Text = "0"
    txtBats.Text = "0"
    txtDreads.Text = "0"
    txtHC.Text = "0"
    
    txtFI.Text = "0"
    txtFB.Text = "0"
    txtAI.Text = "0"
    txtHB.Text = "0"
    txtFang.Text = "0"
    txtWasp.Text = "0"
    txtDagger.Text = "0"
    
    txtAegis.Text = "0"
    txtAnvil.Text = "0"
    txtAsp.Text = "0"
    txtAvalanche.Text = "0"
    txtBadger.Text = "0"
    txtBarracuda.Text = "0"
    txtBerzerker.Text = "0"
    txtCollector.Text = "0"
    txtColossus.Text = "0"
    txtCrusader.Text = "0"
    txtDragon.Text = "0"
    txtGoliath.Text = "0"
    txtHammer.Text = "0"
    txtHurricane.Text = "0"
    txtInterdictor.Text = "0"
    txtJudicator.Text = "0"
    txtLeopard.Text = "0"
    txtOrca.Text = "0"
    txtPrivateer.Text = "0"
    txtRaven.Text = "0"
    txtTangler.Text = "0"
    txtTerrapin.Text = "0"
    txtTortoise.Text = "0"
    txtVespa.Text = "0"
    txtWayfarer.Text = "0"
    txtZephyer.Text = "0"
    txtMaelstrom.Text = "0"
    txtSandstorm.Text = "0"
End Sub

Private Sub cmdClearDefender_Click()
    txtScoutDEF.Text = "0"
    txtDRScoutDEF.Text = "0"
    txtFrigateDEF.Text = "0"
    txtImpFrigateDEF.Text = "0"
    txtDestroyerDEF.Text = "0"
    txtFSDDEF.Text = "0"
    txtCruiserDEF.Text = "0"
    txtHCruiserDEF.Text = "0"
    txtLCDEF.Text = "0"
    txtBatsDEF.Text = "0"
    txtDreadsDEF.Text = "0"
    txtHCDEF.Text = "0"
    
    txtFIDEF.Text = "0"
    txtFBDEF.Text = "0"
    txtAIDEF.Text = "0"
    txtHBDEF.Text = "0"
    txtFangDEF.Text = "0"
    txtWaspDEF.Text = "0"
    txtDaggerDEF.Text = "0"
    
    txtAegisDEF.Text = "0"
    txtAnvilDEF.Text = "0"
    txtAspDEF.Text = "0"
    txtAvalancheDEF.Text = "0"
    txtBadgerDEF.Text = "0"
    txtBarracudaDEF.Text = "0"
    txtBerzerkerDEF.Text = "0"
    txtCollectorDEF.Text = "0"
    txtColossusDEF.Text = "0"
    txtCrusaderDEF.Text = "0"
    txtDragonDEF.Text = "0"
    txtGoliathDEF.Text = "0"
    txtHammerDEF.Text = "0"
    txtHurricaneDEF.Text = "0"
    txtInterdictorDEF.Text = "0"
    txtJudicatorDEF.Text = "0"
    txtLeopardDEF.Text = "0"
    txtOrcaDEF.Text = "0"
    txtPrivateerDEF.Text = "0"
    txtRavenDEF.Text = "0"
    txtDrone.Text = "0"
    txtTanglerDEF.Text = "0"
    txtTerrapinDEF.Text = "0"
    txtTortoiseDEF.Text = "0"
    txtVespaDEF.Text = "0"
    txtWayfarerDEF.Text = "0"
    txtZephyerDEF.Text = "0"
    txtSandstormDEF.Text = "0"
    txtMaelstromDEF.Text = "0"
    
    txtJumpgate.Text = "0"
    txtImpJumpgate.Text = "0"
    txtJumpgate.Text = "0"
    txtFolder = "0"
    txtStarbaseDEF.Text = "0"
    txtODMDEF.Text = "0"
    txtODPDEF.Text = "0"
    txtIMPODPDEF.Text = "0"
    txtOSDEF.Text = "0"
    txtIMPOSDEF.Text = "0"
    txtOCY.Text = "0"
    txtIMPOCY.Text = "0"
    txtOBDEF.Text = "0"
    txtOMDEF.Text = "0"
    txtIMPOMDEF.Text = "0"
    txtSat.Text = "0"
    txtImpSat.Text = "0"
    
    txtSSG.Text = "0"
    txtIMPSSG.Text = "0"
    txtSDB.Text = "0"
    txtIMPSDB.Text = "0"
    txtTurret.Text = "0"
    txtMonolith.Text = "0"
End Sub

Private Sub cmdTest_Click()
    Dim intTestCaseNumber As Integer
    intTestCaseNumber = txtTestNumber.Text
    
    cmdClearAttacker_Click
    cmdClearDefender_Click
    
    Select Case intTestCaseNumber
    
    Case 1:
        txtFSD.Text = 10
        txtDestroyer.Text = 40
        
        txtHBDEF.Text = 44
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optNormal.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attackers losses were 2 Fire Support Destroyer(s) and 350 personnel."
        Log "", "Defenders losses were 44 Heavy Bomber(s) and 132 personnel."
        
    Case 2:
        txtAttackerOffense.Text = 10
        txtHB.Text = 45
        
        txtDestroyerDEF.Text = 15
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attackers losses were 2 Destroyer(s) and 320 personnel."
        Log "", "Defenders losses were 45 Heavy Bomber(s) and 135 personnel."
        
     Case 3:
        txtDestroyer.Text = 10
        txtLeopard.Text = 6
        
        txtFSDDEF.Text = 12
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optNormal.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attackers losses were 5 Leopard Strike Cruiser(s) and 4000 personnel."
        Log "", "Defenders losses were 12 Fire Support Destroyer(s) and 2100 personnel."
        
    Case 4:
        txtFI.Text = 1
        txtHB.Text = 115
        txtDestroyer.Text = 240
        txtFSD.Text = 200
        txtLC.Text = 3
        txtAsp.Text = 45
        txtBats.Text = 50
        txtDreads.Text = 5
        txtDragon.Text = 12
        txtColossus.Text = 3
        
        
        txtIMPOMDEF.Text = 2
        txtStarbaseDEF.Text = 7
        txtFangDEF.Text = 1
        txtHBDEF.Text = 649
        txtDRScoutDEF.Text = 2
        txtImpFrigateDEF.Text = 50
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attackers losses were 1 Fighter Interceptor(s) 115 Heavy Bomber(s) 96 Destroyer(s) and 15706 personnel."
        Log "", "Defenders losses were 649 Heavy Bomber(s) 2 Deep Recon Scout(s) 50 Improved Frigate(s) 1 Fang Fighter Bomber(s) and 21249 personnel."
        Log "", "Defender lost 2 Orbital Minefield (Improved)(s) 7 Starbase(s) ."
        
    Case 5:
        txtAI.Text = 25
        txtFrigate.Text = 5
        txtFSD.Text = 5
        txtDestroyer.Text = 186
        txtCruiser.Text = 5
        txtAsp.Text = 29
        txtDragon.Text = 10
        txtColossus.Text = 1
       
        txtFBDEF.Text = 380
        txtHBDEF.Text = 82
        txtDRScoutDEF.Text = 1
        txtFrigateDEF.Text = 60
        txtImpFrigateDEF.Text = 29
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
       
        Log "", ""
        Log "", "Attackers losses were 25 Advanced Interceptor(s) 5 Frigate(s) 32 Destroyer(s) 5 Fire Support Destroyer(s) and 6545 personnel."
        Log "", "Defenders losses were 380 Fighter Bomber(s) 82 Heavy Bomber(s) 1 Deep Recon Scout(s) 60 Frigate(s) 29 Improved Frigate(s) and 10076 personnel."
   
     Case 6:
        txtFB.Text = 95
        txtHB.Text = 445
        txtDestroyer.Text = 17
        txtFSD.Text = 24
        txtAsp.Text = 25
        txtLC.Text = 36
        txtCrusader.Text = 1
        txtWayfarer.Text = 1
        txtBats.Text = 16
        txtDragon.Text = 18
        
        txtIMPOMDEF.Text = 4
        txtOMDEF.Text = 4
        txtStarbaseDEF.Text = 7
        txtHBDEF.Text = 230
        txtFBDEF.Text = 420
        txtDRScoutDEF.Text = 1
        txtFrigateDEF.Text = 60
        txtImpFrigateDEF.Text = 29
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attackers losses were 95 Fighter Bomber(s) 445 Heavy Bomber(s) 17 Destroyer(s) 24 Fire Support Destroyer(s) 10 Asp Heavy Cruiser(s) and 16445 personnel."
        Log "", "Defenders losses were 55 Heavy Bomber(s) and 14165 personnel."
        Log "", "Defender lost 4 Orbital Minefield(s) 4 Orbital Minefield (Improved)(s) 7 Starbase(s) ."

    Case 7:
        txtDefenderDefense = 5
        
        txtHB.Text = 196
        txtAI.Text = 29
        txtInterdictor.Text = 4
        txtFSD.Text = 41
        txtLC.Text = 15
        txtCruiser.Text = 10
        txtBats.Text = 9
             
        txtStarbaseDEF.Text = 4
        txtIMPOMDEF.Text = 10
        txtODMDEF.Text = 7
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attackers losses were 29 Advanced Interceptor(s) 196 Heavy Bomber(s) and 646 personnel."
        Log "", "Defender lost 10 Orbital Minefield (Improved)(s) 4 Starbase(s) 7 Orbital Defense Monitor(s)."

    Case 8:
        txtDestroyer.Text = 8
             
        txtHBDEF.Text = 175
        txtFBDEF.Text = 420
        txtDRScoutDEF.Text = 1
        txtFrigateDEF.Text = 60
        txtImpFrigateDEF.Text = 29
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optNormal.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attackers losses were 8 Destroyer(s) and 1280 personnel."
        Log "", "Defenders losses were 23 Heavy Bomber(s) and 69 personnel."

    Case 9:
        'hit and run attack
        txtDestroyer.Text = 40
        txtCruiser.Text = 11
        
        txtOMDEF.Text = 3
        txtFIDEF.Text = 50
        txtFBDEF.Text = 20
        txtScoutDEF.Text = 10
        txtFrigateDEF.Text = 15
        txtCruiserDEF.Text = 10
        
        'Volleys
        txtVolleys.Text = 1
        'Attack Formation
        optNormal.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attackers losses were 4 Cruiser(s) and 3200 personnel."
        Log "", "Defenders losses were 50 Fighter Interceptor(s) 20 Fighter Bomber(s) 10 Scout(s) 6 Frigate(s) and 930 personnel."
        Log "", "Defender lost 3 Orbital Minefield(s)."
           
    Case 10:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtFSD.Text = 55

        txtOMDEF.Text = 30
        txtOSDEF.Text = 10
        txtJumpgate.Text = 1
        txtIMPOCY.Text = 1
        txtTortoiseDEF.Text = 1
        txtCruiserDEF.Text = 2
        txtLCDEF.Text = 5
        txtRavenDEF.Text = 1
        txtFSDDEF.Text = 10
        txtImpFrigateDEF.Text = 10
        txtDRScoutDEF.Text = 4
        txtScoutDEF.Text = 2
        txtWaspDEF.Text = 180
        txtHBDEF.Text = 40
        txtDrone.Text = 1000
                
        'Volleys
        txtVolleys.Text = 1
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click

        Log "", ""
        Log "", "Attacker's losses were 41 Fire Support Destroyer(s) and 7175 personnel."
        Log "", "Defender's losses were 958 Stinger Drone(s) and 0 personnel."
        Log "", "Defender lost 15 Orbital Minefield(s)."
        
    Case 11:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtHB.Text = 70
        txtAI.Text = 230
        txtScout.Text = 75
        txtDRScout.Text = 55
        txtBarracuda.Text = 1
        txtFSD.Text = 200
        txtBats.Text = 20
        txtBats.Text = 19
        txtDreads.Text = 127
        txtOrca.Text = 4
        
             
        txtStarbaseDEF.Text = 7
        txtDrone.Text = 200
        txtWaspDEF.Text = 1397
        txtHBDEF.Text = 150
        txtAIDEF.Text = 3
        txtScoutDEF.Text = 1
        txtDRScoutDEF.Text = 7
        txtDestroyerDEF.Text = 10
        txtFSDDEF.Text = 325
        txtBatsDEF.Text = 16
        txtOrcaDEF.Text = 2
        txtDreadsDEF.Text = 37
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 230 Advanced Interceptor(s) 70 Heavy Bomber(s) 75 Scout(s) 55 Deep Recon Scout(s) 200 Fire Support Destroyer(s) 19 Battleship(s) 105 Dreadnought(s) 20 Light Carrier(s) 1 Barracuda Attack Frigate(s) 4 Orca Battleship(s) and 282745 personnel."
        Log "", "Defender's losses were 248 Wasp Fighter(s) 200 Stinger Drone(s) and 14248 personnel."
        Log "", "Defender lost 7 Starbase(s) ."
    Case 12:
        txtAttackerOffense.Text = 5
        
        txtFSD.Text = 180
        txtBats.Text = 3
        txtLC.Text = 8
        txtDreads.Text = 82
        txtAI.Text = 120
        txtOrca.Text = 4
        
        txtDrone.Text = 750
        txtWaspDEF.Text = 1231
        txtHBDEF.Text = 150
        txtDRScoutDEF.Text = 6
        txtFSDDEF.Text = 260
        txtVespaDEF.Text = 5
        txtDreadsDEF.Text = 30
        'For testing we add some buildings
        txtStarbaseDEF.Text = 7
        txtOSDEF.Text = 1
        txtIMPOCY.Text = 1

        'Volleys
        txtVolleys.Text = 1
        'Attack Formation
        optFleetRecon.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 114 Fire Support Destroyer(s) and 19950 personnel."
        Log "", "Defender's losses were 108 Wasp Fighter(s) 750 Stinger Drone(s) and 108 personnel."
        
    Case 13:
        txtInterdictor.Text = 30
        txtFrigate.Text = 10
        
        txtDRScoutDEF.Text = 6
        txtDrone.Text = 200
        
        'Volleys hit and run
        txtVolleys.Text = 1
        'Attack Formation
        optNormal.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 23 Interdictor Frigate(s) and 2300 personnel."
        Log "", "Defender's losses were 195 Stinger Drone(s) and 0 personnel."
        
     Case 14:
        txtAttackerOffense.Text = 5
        
        txtFB.Text = 70
        txtHB.Text = 15
        txtFrigate.Text = 22
        txtDestroyer.Text = 5
        txtFSD.Text = 26
        txtLC.Text = 3
        txtBats.Text = 32
        txtTangler.Text = 4
        txtDragon.Text = 16
        
        txtBatsDEF.Text = 13
        txtFSDDEF.Text = 250
        txtScoutDEF.Text = 1
        
        'Volleys hit and run
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 70 Fighter Bomber(s) 15 Heavy Bomber(s) 22 Frigate(s) 5 Destroyer(s) 26 Fire Support Destroyer(s) 28 Battleship(s) 3 Light Carrier(s) and 55175 personnel"
        Log "", "Defender's losses were 1 Scout(s) 250 Fire Support Destroyer(s) 13 Battleship(s) and 64574 personnel."
    
    Case 15:
        txtAttackerOffense.Text = 5
        
        txtHB.Text = 40
        txtFSD.Text = 38
        txtTangler.Text = 4
        txtDragon.Text = 24
        
        txtOBDEF.Text = 4
        txtODPDEF.Text = 4
        txtDreadsDEF.Text = 7
        txtFSDDEF.Text = 50
        txtVespaDEF.Text = 4
        txtDRScoutDEF.Text = 5
        txtDaggerDEF.Text = 1
        txtHBDEF.Text = 120
        txtDrone.Text = 1000
        
        'Volleys hit and run
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optFighterSpread.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 40 Heavy Bomber(s) 38 Fire Support Destroyer(s) 3 Tangler Defense Barge(s) and 10370 personnel."
        Log "", "Defender's losses were 61 Heavy Bomber(s) 5 Deep Recon Scout(s) 50 Fire Support Destroyer(s) 7 Dreadnought(s) 1000 Stinger Drone(s) 4 Vespa Siege Carrier(s) 1 Dagger Heavy Fighter(s) and 29020 personnel."
        Log "", "Defender lost 4 Orbital Defense Platform(s) 4 Orbital Bulwark(s) ."
     
    Case 16:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtBarracuda.Text = 61
        txtFSD.Text = 22
        txtFI.Text = 115
        txtJudicator.Text = 6
        txtBats.Text = 8
        txtLC.Text = 5
        txtHB.Text = 20
        txtDragon.Text = 1
        txtTerrapin.Text = 1
        
        txtIMPOCY.Text = 1
        txtDreadsDEF.Text = 2
        txtBatsDEF.Text = 16
        txtJudicatorDEF.Text = 2
               
        'Volleys sensorblind
        txtVolleys.Text = 3
        'Attack Formation
        optSensorBlind.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 10 Fire Support Destroyer(s) 61 Barracuda Attack Frigate(s) and 7850 personnel."
        Log "", "Defender's losses were 16 Battleship(s) 2 Dreadnought(s) 2 Judicator Dreadnought(s) and 34100 personnel."
        Log "", "Defender lost 1 Orbital Construction Yard(s) 1 Orbital Construction Yard (Improved)(s) 10 Satellites(s) ."
    
    Case 17:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtDRScout.Text = 49
        txtScout.Text = 12
        txtJudicator.Text = 6
        txtFSD.Text = 12
        txtFI.Text = 115
        txtOrca.Text = 20
        txtTerrapin.Text = 1
        txtDragon.Text = 1
        txtBats.Text = 8
        txtLC.Text = 5
        txtHB.Text = 20
        
        txtIMPOMDEF.Text = 32
        txtOMDEF.Text = 5
        txtOSDEF.Text = 8
        txtSat.Text = 10
        txtIMPOCY.Text = 1
        txtJumpgate.Text = 1
        txtFSDDEF.Text = 61
        txtDRScoutDEF.Text = 13
        txtDaggerDEF.Text = 60
        txtAIDEF.Text = 74
        txtFBDEF.Text = 10
        txtHBDEF.Text = 75
        txtFangDEF.Text = 1
  
        'Volleys sensorblind
        txtVolleys.Text = 1
        'Attack Formation
        optSensorBlind.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 10 Fire Support Destroyer(s) 61 Barracuda Attack Frigate(s) and 7850 personnel."
        Log "", "Defender's losses were 16 Battleship(s) 2 Dreadnought(s) 2 Judicator Dreadnought(s) and 34100 personnel."
        Log "", "Defender lost 1 Orbital Construction Yard(s) 1 Orbital Construction Yard (Improved)(s) 10 Satellites(s) ."
    
    Case 18:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtDRScout.Text = 6
        txtScout.Text = 33
        txtBarracuda.Text = 72
        txtDestroyer.Text = 10
        txtFSD.Text = 198
        txtOrca.Text = 16
        
        txtOSDEF.Text = 9
        txtODPDEF.Text = 8
        txtIMPOCY.Text = 1
        txtJumpgate.Text = 1
        txtDrone.Text = 451
        txtFBDEF.Text = 50
        txtDaggerDEF.Text = 40
        txtHBDEF.Text = 90
        txtDRScoutDEF.Text = 4
        txtInterdictorDEF.Text = 22
        txtImpFrigateDEF.Text = 8
        txtHammerDEF.Text = 21
        txtDestroyerDEF.Text = 21
        txtFSDDEF.Text = 30
        txtCruiserDEF.Text = 8
        txtBatsDEF.Text = 14
        txtJudicatorDEF.Text = 6
        
        'Volleys sensorblind
        txtVolleys.Text = 3
        'Attack Formation
        optSensorBlind.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 33 Scout(s) 6 Deep Recon Scout(s) 10 Destroyer(s) 46 Fire Support Destroyer(s) 72 Barracuda Attack Frigate(s) 16 Orca Battleship(s) and 44992 personnel."
        Log "", "Defender's losses were 50 Fighter Bomber(s) 90 Heavy Bomber(s) 4 Deep Recon Scout(s) 8 Improved Frigate(s) 21 Destroyer(s) 30 Fire Support Destroyer(s) 8 Cruiser(s) 3 Battleship(s) 451 Stinger Drone(s) 22 Interdictor Frigate(s) 21 Hammer Gunship(s) 40 Dagger Heavy Fighter(s) and 27110 personnel."
        Log "", "Defender lost 8 Orbital Defense Platform(s) 9 Orbital Shield(s) 1 Orbital Construction Yard (Improved)(s) 1 Jumpgate(s) ."
            
    Case 19:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtFSD.Text = 19
        txtFrigate.Text = 7
        txtBats.Text = 9
        txtFB.Text = 45
        txtLC.Text = 3

        txtJumpgate.Text = 1
        txtIMPOCY.Text = 2
        txtHBDEF.Text = 85
        txtAIDEF.Text = 105
        txtHammerDEF.Text = 20
        txtDragonDEF.Text = 1
      
  
        'Volleys sensorblind
        txtVolleys.Text = 3
        'Attack Formation
        optSensorBlind.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 1 Frigate(s) 19 Fire Support Destroyer(s) and 3425 personnel."
        Log "", "Defender's losses were 7 Advanced Interceptor(s) 85 Heavy Bomber(s) and 2169 personnel."
        Log "", "Defender lost 2 Orbital Construction Yard (Improved)(s) 1 Jumpgate(s) ."
        
    Case 20:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtFSD.Text = 25
        txtTangler.Text = 1
        txtDestroyer.Text = 5
        txtFB.Text = 10
        
        txtJumpgate.Text = 1
        txtOCY.Text = 1
        txtSat.Text = 5
        
        'Volleys sensorblind
        txtVolleys.Text = 3
        'Attack Formation
        optSensorBlind.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Defender lost 1 Orbital Construction Yard(s) 5 Satellites(s) 1 Jumpgate(s) ."

    Case 21:
        txtAttackerOffense.Text = 5
        
        txtBarracuda.Text = 43
        txtFI.Text = 58
        txtFSD.Text = 13
        txtDestroyer.Text = 15
        txtAI.Text = 5
        txtBadger.Text = 22
        txtHB.Text = 42
        txtLC.Text = 7
        txtCruiser.Text = 15
        txtAnvil.Text = 5
        txtTortoise.Text = 2
        txtBats.Text = 1
        txtDreads.Text = 6
        
        
        txtOSDEF.Text = 4
        txtIMPOCY.Text = 2
        txtImpJumpgate.Text = 1
        txtDRScoutDEF.Text = 10
        txtWayfarerDEF.Text = 1
        
        'Volleys sensorblind
        txtVolleys.Text = 3
        'Attack Formation
        optSensorBlind.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 2 Barracuda Attack Frigate(s) and 200 personnel."
        Log "", "Defender's losses were 10 Deep Recon Scout(s) 1 Wayfarer Exploration Cruiser(s) and 3500 personnel."
        Log "", "Defender lost 4 Orbital Shield(s) 2 Orbital Construction Yard (Improved)(s) 1 Jumpgate (Improved)(s) ."
    
     Case 22:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtDragon.Text = 5
        txtGoliath.Text = 10
        
        txtOSDEF.Text = 2
        txtIMPOSDEF.Text = 5
        txtODPDEF.Text = 5
        txtJumpgate.Text = 1
        txtIMPOCY.Text = 1
        txtOCY.Text = 1
        txtSSG.Text = 15
        txtIMPSSG.Text = 7
        txtTurret.Text = 78

        'Volleys bombard
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 0
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 10 Goliath Battleship(s) 5 Dragon Mobile Assault Platform(s) and 32000 personnel."
        Log "", "Defender lost 2 Orbital Shield(s) 2 Orbital Shield (Improved)(s) ."
      
    Case 23:
        txtBats.Text = 8
        
        txtOSDEF.Text = 4
        txtJumpgate.Text = 1
        txtSSG.Text = 16
        txtTurret.Text = 50

        'Volleys bombard
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 0
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 8 Battleship(s) and 12800 personnel."
        Log "", "Defender lost 2 Orbital Shield(s) ."
        
    Case 24:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtAnvil.Text = 15
        txtJudicator.Text = 12
        txtBats.Text = 1
        txtCrusader.Text = 14
        txtPrivateer.Text = 1
        txtWayfarer.Text = 1
        txtHB.Text = 105
        txtFang.Text = 1
        txtFB.Text = 4
        txtDRScout.Text = 16
        txtLC.Text = 7
        
        txtSSG.Text = 13
        txtSDB.Text = 20

        'Volleys bombard
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 0
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 92 Heavy Bomber(s) 1 Battleship(s) 15 Anvil Battleship(s) 12 Judicator Dreadnought(s) 1 Wayfarer Exploration Cruiser(s) 14 Crusader Battlecruiser(s) 1 Privateer Heavy Cruiser(s) and 60876 personnel."
        Log "", "Defender lost 12 Surface Shield Generator(s) ."
    
    Case 25:
        txtAttackerOffense.Text = 5
        txtDefenderOffense.Text = 5
        
        txtDreads.Text = 62
        txtFSD.Text = 32
        
        txtStarbaseDEF.Text = 1
        txtIMPSSG.Text = 17
        txtIMPSDB.Text = 6
        txtTurret.Text = 76
        
        'Volleys bombard
        txtVolleys.Text = 1
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 0
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 15 Dreadnought(s) and 27000 personnel."
        Log "", "Defender lost 2 Surface Shield Generator (Improved)(s) 1 Starbase(s) ."
    
    Case 26:
        txtAttackerOffense.Text = 55
        txtAttackerDefense.Text = 35
        txtAttackerDurability.Text = 17
        
        txtDefenderOffense.Text = 50
        txtDefenderDefense.Text = 60
        txtDefenderDurability.Text = 65
        
        txtFI.Text = 50
        txtFB.Text = 74
        txtAI.Text = 101
        txtInterdictor.Text = 50
        txtFSD.Text = 46
        txtPrivateer.Text = 4
        txtBats.Text = 4
        txtJudicator.Text = 35
        txtTortoise.Text = 4
        txtDreads.Text = 2
        txtVespa.Text = 2
        
        txtStarbaseDEF.Text = 11
        txtIMPOSDEF.Text = 2
        txtIMPODPDEF.Text = 7
        txtMaelstromDEF.Text = 13
        txtTanglerDEF.Text = 1
        txtImpFrigateDEF.Text = 45
        txtDRScoutDEF.Text = 31
        txtAIDEF.Text = 404
        txtHBDEF.Text = 406
        
        'Volleys bombard
        txtVolleys.Text = 1
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 50 Fighter Interceptor(s) 101 Advanced Interceptor(s) 74 Fighter Bomber(s) 31 Interdictor Frigate(s) and 3500 personnel."
        Log "", "Defender lost 2 Orbital Shield (Improved)(s) . "
    
    Case 27:
        txtAttackerOffense.Text = 28
        txtAttackerDefense.Text = 28
        txtAttackerDurability.Text = 23
        
        txtDefenderOffense.Text = 28
        txtDefenderDefense.Text = 70
        txtDefenderDurability.Text = 30
        
        txtScout.Text = 3010
        txtDRScout.Text = 1279
        
        txtStarbaseDEF.Text = 5
        txtIMPODPDEF.Text = 2
        txtIMPOMDEF.Text = 12
        txtDrone.Text = 36
        
        txtAIDEF.Text = 14
        txtHBDEF.Text = 533
        txtDRScoutDEF.Text = 75
        txtFSDDEF.Text = 606
        txtDreadsDEF.Text = 90
        txtHCDEF.Text = 8
        
        'Volleys
        txtVolleys.Text = 5
        'Attack Formation
        optFighterScreen.Value = True
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        
        Log "", ""
        Log "", "Attacker's losses were 3010 Scout(s) 1279 Deep Recon Scout(s) and 104215 personnel."
        Log "", "Defender lost 12 Orbital Minefield (Improved)(s) 2 Orbital Defense Platform (Improved)(s) 5 Starbase(s) 1 Remote Sensor Array(s)"
    
    Case 28:
        txtAttackerOffense.Text = 0
        txtAttackerDefense.Text = 0
        txtAttackerDurability.Text = 0
        
        txtDefenderOffense.Text = 0
        txtDefenderDefense.Text = 0
        txtDefenderDurability.Text = 0
        
        txtHB.Text = 60
        txtFB.Text = 30
        txtLC.Text = 6
        
        txtSDB.Text = 15
        
        'Volleys
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 1
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 30 Fighter Bomber(s) 60 Heavy Bomber(s) 6 Light Carrier(s) and 5520 personnel."
        Log "", "Defender lost 1 Surface Defense Battery(s) ."
   
    Case 29:
        txtAttackerOffense.Text = 25
        txtAttackerDefense.Text = 0
        txtAttackerDurability.Text = 10
        
        txtDefenderOffense.Text = 0
        txtDefenderDefense.Text = 0
        txtDefenderDurability.Text = 0

        txtDreads.Text = 2
        txtBats.Text = 28
        
        txtOMDEF.Text = 66
        txtSSG.Text = 20
        
        'Volleys
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 1
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 10 Battleship(s) 2 Dreadnought(s) and 19600 personnel."
        Log "", "Defender lost 66 Orbital Minefield(s) 7 Surface Shield Generator(s) "
        
    Case 30:
        txtAttackerOffense.Text = 0
        txtAttackerDefense.Text = 0
        txtAttackerDurability.Text = 0
        
        txtDefenderOffense.Text = 0
        txtDefenderDefense.Text = 0
        txtDefenderDurability.Text = 0
        
        txtGoliath.Text = 50
        
        txtIMPSDB.Text = 22
        
        'Volleys
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 1
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 13 Goliath Battleship(s) and 23400 personnel"
        Log "", "Defender lost 22 Surface Defense Battery (Improved)(s)"
        
    Case 31:
        txtAttackerOffense.Text = 35
        txtAttackerDefense.Text = 75
        txtAttackerDurability.Text = 35
        
        txtDefenderOffense.Text = 35
        txtDefenderDefense.Text = 50
        txtDefenderDurability.Text = 60
        
        txtGoliath.Text = 40
        txtDreads.Text = 30
        
        txtSSG.Text = 2
        txtIMPSSG.Text = 10
        txtIMPSDB.Text = 18
        
        'Volleys
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 1
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 30 Goliath Battleship(s) and 54000 personnel."
        Log "", "Defender lost 8 Surface Shield Generator (Improved)(s)"
        
    Case 32:
        txtAttackerOffense.Text = 0
        txtAttackerDefense.Text = 0
        txtAttackerDurability.Text = 0
        
        txtDefenderOffense.Text = 0
        txtDefenderDefense.Text = 0
        txtDefenderDurability.Text = 0
        
        txtMaelstrom.Text = 8
        txtDragon.Text = 8
        txtAnvil.Text = 5
        
        txtSSG.Text = 3
        txtIMPSSG.Text = 3
        txtIMPSDB.Text = 16
        
        'Volleys
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 1
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 6 Dragon Mobile Assault Platform(s) 8 Maelstrom Siege Platform(s) and 40800 personnel."
        Log "", "Defender lost 2 Surface Shield Generator(s) 3 Surface Shield Generator (Improved)(s) "
        
    Case 33:
        txtAttackerOffense.Text = 40
        txtAttackerDefense.Text = 70
        txtAttackerDurability.Text = 35
        
        txtDefenderOffense.Text = 35
        txtDefenderDefense.Text = 35
        txtDefenderDurability.Text = 35
        
        txtAegis.Text = 30
        txtDragon.Text = 38
        txtAnvil.Text = 1
        txtDreads.Text = 149
        txtBats.Text = 4
    
        txtIMPSSG.Text = 45
        txtIMPSDB.Text = 54
        
        'Volleys
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 1
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 1 Anvil Battleship(s) 30 Aegis Mobile Shield(s) 24 Dragon Mobile Assault Platform(s) and 98900 personnel."
        Log "", "Defender lost 12 Surface Defense Battery (Improved)(s) 45 Surface Shield Generator (Improved)(s) "

    Case 34:
        txtAttackerOffense.Text = 40
        txtAttackerDefense.Text = 70
        txtAttackerDurability.Text = 35
        
        txtDefenderOffense.Text = 35
        txtDefenderDefense.Text = 35
        txtDefenderDurability.Text = 35
        
        txtAegis.Text = 6
        txtDragon.Text = 14
        txtDreads.Text = 206
        txtBats.Text = 38
        txtFSD.Text = 115
    
        txtSSG.Text = 32
        txtIMPSSG.Text = 20
        txtIMPSDB.Text = 53
        
        'Volleys
        txtVolleys.Text = 3
        'Attack Formation
        optBombard.Value = True
        chkRandom.Value = 1
        'Defense Formation
        optStandard.Value = True
        
        cmdFight_Click
        
        Log "", ""
        Log "", "Attacker's losses were 59 Dreadnought(s) 6 Aegis Mobile Shield(s) 14 Dragon Mobile Assault Platform(s) and 151400 personnel"
        Log "", "Defender lost 53 Surface Defense Battery (Improved)(s) 32 Surface Shield Generator(s) 20 Surface Shield Generator (Improved)(s) 1 Barracks(s) 1 Barracks (Veteran)(s) 24 Manufacturing Plant (Improved)(s) "

    End Select
End Sub

Private Sub Form_Load()
    'Fill the formula combobx
    cmbFormula.AddItem "Base", 0
    cmbFormula.AddItem "Metallikov", 1

    ReadINIFile
    
    'Move the window in the middle
    Me.Move (Screen.Width - Me.Width) \ 2, (Screen.Height - Me.Height) \ 2
    
    DEV = False
    'DEV = True
    
    If DEV Then
        'do nothing
    Else
        'cmdSort.Visible = False
        'cmdFormation.Visible = False
    End If
    
End Sub

Private Sub cmdSort_Click()
    ComposeFleet
    
    lstAttacker.Clear
    FillListbox
End Sub

Public Function SortItemCollectionDefender(col As colShipsDefender, strPropertyName, Optional blnCompareNumeric As Boolean = False, Optional SortOrder As Boolean = True) As colShipsDefender
    Dim colNew As colShipsDefender
    Dim objCurrent As Object
    Dim objCompare As Object
    Dim lngCompareIndex As Integer
    Dim strCurrent As String
    Dim strCompare As String
    Dim blnGreaterValueFound As Boolean
    Dim strName As String
    Dim intI As Integer
    Dim intJ As Integer
    'make a copy of the collection, ripping through it one item
    'at a time, adding to new collection in right order...
    
    Set colNew = New colShipsDefender
    
    For intI = 1 To col.Count
        Set objCurrent = col.Item(intI, False)
    'For Each objCurrent In col
    
        'get value of current item...
        strCurrent = CallByName(objCurrent, strPropertyName, VbGet)
        strName = CallByName(objCurrent, "Name", VbGet)
        
        'setup for compare loop
        blnGreaterValueFound = False
        lngCompareIndex = 0
        
        'For Each objCompare In colNew
        For intJ = 1 To colNew.Count
            Set objCompare = colNew.Item(intJ, False)
            
            lngCompareIndex = lngCompareIndex + 1
            
            strCompare = CallByName(objCompare, strPropertyName, VbGet)
            
            'optimization - instead of doing this for every iteration, have 2 different loops...
            If blnCompareNumeric = True Then
                'this means we are looking for a numeric sort order...
                
                If SortOrder Then
                    'Ascending
                    If Val(strCurrent) > Val(strCompare) Then
                        'found an item in compare collection that is greater...
                        'add it to the new collection...
                        blnGreaterValueFound = True
                        colNew.AddBefore objCurrent, lngCompareIndex
                        Exit For
                    End If
                Else
                    'Descending
                        If Val(strCurrent) < Val(strCompare) Then
                        'found an item in compare collection that is greater...
                        'add it to the new collection...
                        blnGreaterValueFound = True
                        colNew.AddBefore objCurrent, lngCompareIndex
                        Exit For
                    End If
                End If
            Else
                'this means we are looking for a string sort...
                If SortOrder Then
                    'Ascending
                    If strCurrent > strCompare Then
                        'found an item in compare collection that is greater...
                        'add it to the new collection...
                        blnGreaterValueFound = True
                        colNew.AddBefore objCurrent, lngCompareIndex
                        Exit For
                    End If
                Else
                    'Descending
                    If strCurrent < strCompare Then
                        'found an item in compare collection that is greater...
                        'add it to the new collection...
                        blnGreaterValueFound = True
                        colNew.AddBefore objCurrent, lngCompareIndex
                        Exit For
                    End If
                End If
            End If
         Next intJ
        
        'if we didn't find something bigger, just add it to the end of the new collection...
        If blnGreaterValueFound = False Then
            colNew.Add objCurrent
        End If
              
    Next intI

    'return the new collection...
    Set SortItemCollectionDefender = colNew
    Set colNew = Nothing

End Function

Public Function SortItemCollectionAttacker(col As colShipsAttacker, strPropertyName, Optional blnCompareNumeric As Boolean = False, Optional SortOrder As Boolean = True) As colShipsAttacker
    Dim colNew As colShipsAttacker
    Dim objCurrent As Object
    Dim objCompare As Object
    Dim lngCompareIndex As Integer
    Dim strCurrent As String
    Dim strCompare As String
    Dim blnGreaterValueFound As Boolean
    Dim strName As String
    Dim intI As Integer
    Dim intJ As Integer
    'make a copy of the collection, ripping through it one item
    'at a time, adding to new collection in right order...
    
    Set colNew = New colShipsAttacker
    
    For intI = 1 To col.Count
        Set objCurrent = col.Item(intI, True)
    'For Each objCurrent In col
    
        'get value of current item...
        strCurrent = CallByName(objCurrent, strPropertyName, VbGet)
        strName = CallByName(objCurrent, "Name", VbGet)
        
        'setup for compare loop
        blnGreaterValueFound = False
        lngCompareIndex = 0
        
        'For Each objCompare In colNew
        For intJ = 1 To colNew.Count
            Set objCompare = colNew.Item(intJ, True)
            
            lngCompareIndex = lngCompareIndex + 1
            
            strCompare = CallByName(objCompare, strPropertyName, VbGet)
            
            'optimization - instead of doing this for every iteration, have 2 different loops...
            If blnCompareNumeric = True Then
                'this means we are looking for a numeric sort order...
                
                If SortOrder Then
                    'Ascending
                    If Val(strCurrent) > Val(strCompare) Then
                        'found an item in compare collection that is greater...
                        'add it to the new collection...
                        blnGreaterValueFound = True
                        colNew.AddBefore objCurrent, lngCompareIndex
                        Exit For
                    End If
                Else
                    'Descending
                        If Val(strCurrent) < Val(strCompare) Then
                        'found an item in compare collection that is greater...
                        'add it to the new collection...
                        blnGreaterValueFound = True
                        colNew.AddBefore objCurrent, lngCompareIndex
                        Exit For
                    End If
                End If
            Else
                'this means we are looking for a string sort...
                If SortOrder Then
                    'Ascending
                    If strCurrent > strCompare Then
                        'found an item in compare collection that is greater...
                        'add it to the new collection...
                        blnGreaterValueFound = True
                        colNew.AddBefore objCurrent, lngCompareIndex
                        Exit For
                    End If
                Else
                    'Descending
                    If strCurrent < strCompare Then
                        'found an item in compare collection that is greater...
                        'add it to the new collection...
                        blnGreaterValueFound = True
                        colNew.AddBefore objCurrent, lngCompareIndex
                        Exit For
                    End If
                End If
            End If
         Next intJ
        
        'if we didn't find something bigger, just add it to the end of the new collection...
        If blnGreaterValueFound = False Then
            colNew.Add objCurrent
        End If
              
    Next intI

    'return the new collection...
    Set SortItemCollectionAttacker = colNew
    Set colNew = Nothing

End Function

Private Sub AddToAttCollection(attackerShip As clsShip, Optional blnFront As Boolean = False)
    If intAttackerDurability > 0 Then
        attackerShip.Durability = attackerShip.Durability + Round(((attackerShip.Durability * intAttackerDurability) / 100), 0)
    End If
    
    If intAttackerOffense > 0 Then
        attackerShip.OffCap = attackerShip.OffCap + Round(((attackerShip.OffCap * intAttackerOffense) / 100), 0)
        attackerShip.OffFight = attackerShip.OffFight + Round(((attackerShip.OffFight * intAttackerOffense) / 100), 0)
        attackerShip.OffStruct = attackerShip.OffStruct + Round(((attackerShip.OffStruct * intAttackerOffense) / 100), 0)
    End If
    
    If intAttackerDefense > 0 Then
        attackerShip.DefCap = attackerShip.DefCap + Round(((attackerShip.DefCap * intAttackerDefense) / 100), 0)
        attackerShip.DefFight = attackerShip.DefFight + Round(((attackerShip.DefFight * intAttackerDefense) / 100), 0)
    End If
    
    If blnFront Then
        If colAttacker.Count = 0 Then
             colAttacker.Add attackerShip
        Else
             colAttacker.AddBefore attackerShip, 1
        End If
    Else
        colAttacker.Add attackerShip
    End If
End Sub

Private Sub AddToDefCollection(defenderShip As clsShip, Optional blnFront As Boolean = False)
    If intDefenderDurability > 0 Then
        defenderShip.Durability = defenderShip.Durability + Round(((defenderShip.Durability * intDefenderDurability) / 100), 0)
    End If
    
    If intDefenderOffense > 0 Then
        defenderShip.OffCap = defenderShip.OffCap + Round(((defenderShip.OffCap * intDefenderOffense) / 100), 0)
        defenderShip.OffFight = defenderShip.OffFight + Round(((defenderShip.OffFight * intDefenderOffense) / 100), 0)
        defenderShip.OffStruct = defenderShip.OffStruct + Round(((defenderShip.OffStruct * intDefenderOffense) / 100), 0)
    End If
    
    If intDefenderDefense > 0 Then
        defenderShip.DefCap = defenderShip.DefCap + Round(((defenderShip.DefCap * intDefenderDefense) / 100), 0)
        defenderShip.DefFight = defenderShip.DefFight + Round(((defenderShip.DefFight * intDefenderDefense) / 100), 0)
    End If
    
    If blnFront Then
        If colDefender.Count = 0 Then
             colDefender.Add defenderShip
        Else
             colDefender.AddBefore defenderShip, 1
        End If
    Else
        colDefender.Add defenderShip
    End If
    
End Sub

Private Sub AddAttackerFighters(blnFront As Boolean)
    Dim attackerShip As clsShip
    Dim intI As Integer
    
    If blnFront Then
        'Add fighters at front of collection
        For intI = 1 To txtHB.Text
            Set attackerShip = New clsHB
            
            AddToAttCollection attackerShip, blnFront
        Next intI
        
        For intI = 1 To txtFang.Text
            Set attackerShip = New clsFang
            
            AddToAttCollection attackerShip, blnFront
        Next intI
        
        For intI = 1 To txtDagger.Text
            Set attackerShip = New clsDagger
            
           AddToAttCollection attackerShip, blnFront
        Next intI
        
        For intI = 1 To txtFB.Text
            Set attackerShip = New clsFB
            
            AddToAttCollection attackerShip, blnFront
        Next intI
        
        For intI = 1 To txtAI.Text
            Set attackerShip = New clsAI
            
            AddToAttCollection attackerShip, blnFront
        Next intI
        
        For intI = 1 To txtFI.Text
            Set attackerShip = New clsFI
            
            AddToAttCollection attackerShip, blnFront
        Next intI
        
        For intI = 1 To txtWasp.Text
            Set attackerShip = New clsWasp
            
            AddToAttCollection attackerShip, blnFront
        Next intI
    Else
        'Add fighters at back of collection
         For intI = 1 To txtHB.Text
            Set attackerShip = New clsHB
            
            AddToAttCollection attackerShip
        Next intI
        
        For intI = 1 To txtFang.Text
            Set attackerShip = New clsFang
            
            AddToAttCollection attackerShip
        Next intI
        
        For intI = 1 To txtDagger.Text
            Set attackerShip = New clsDagger
            
           AddToAttCollection attackerShip
        Next intI
        
        For intI = 1 To txtFB.Text
            Set attackerShip = New clsFB
            
            AddToAttCollection attackerShip
        Next intI
        
        For intI = 1 To txtAI.Text
            Set attackerShip = New clsAI
            
            AddToAttCollection attackerShip
        Next intI
        
        For intI = 1 To txtFI.Text
            Set attackerShip = New clsFI
            
            AddToAttCollection attackerShip
        Next intI
        
        For intI = 1 To txtWasp.Text
            Set attackerShip = New clsWasp
            
            AddToAttCollection attackerShip
        Next intI
    End If
    
    Set attackerShip = Nothing
End Sub

Private Sub AddDefenderFighters(blnFront As Boolean)
    Dim defenderShip As clsShip
    Dim intI As Integer
    
    If blnFront Then
        'Add fighters at front of collection
         'Fighter Defender
        For intI = 1 To txtWaspDEF.Text
            Set defenderShip = New clsWasp
            
            AddToDefCollection defenderShip, blnFront
        Next intI
        
        For intI = 1 To txtFIDEF.Text
            Set defenderShip = New clsFI
             
            AddToDefCollection defenderShip, blnFront
        Next intI
        
        For intI = 1 To txtAIDEF.Text
            Set defenderShip = New clsAI
            
            AddToDefCollection defenderShip, blnFront
        Next intI
        
        For intI = 1 To txtFBDEF.Text
            Set defenderShip = New clsFB
            
            AddToDefCollection defenderShip, blnFront
        Next intI
        
        For intI = 1 To txtDaggerDEF.Text
            Set defenderShip = New clsDagger
            
            AddToDefCollection defenderShip, blnFront
        Next intI
        
        For intI = 1 To txtFangDEF.Text
            Set defenderShip = New clsFang
            
            AddToDefCollection defenderShip, blnFront
        Next intI
        
        For intI = 1 To txtHBDEF.Text
            Set defenderShip = New clsHB
            
            AddToDefCollection defenderShip, blnFront
        Next intI
    Else
        'Add fighters at back of collection
         'Fighter Defender
         
        For intI = 1 To txtWaspDEF.Text
            Set defenderShip = New clsWasp
            
            AddToDefCollection defenderShip
        Next intI
        
        For intI = 1 To txtFIDEF.Text
            Set defenderShip = New clsFI
             
            AddToDefCollection defenderShip
        Next intI
        
        For intI = 1 To txtAIDEF.Text
            Set defenderShip = New clsAI
            
            AddToDefCollection defenderShip
        Next intI
        
        For intI = 1 To txtFBDEF.Text
            Set defenderShip = New clsFB
            
            AddToDefCollection defenderShip
        Next intI
        
        For intI = 1 To txtDaggerDEF.Text
            Set defenderShip = New clsDagger
            
            AddToDefCollection defenderShip
        Next intI
        
        For intI = 1 To txtFangDEF.Text
            Set defenderShip = New clsFang
            
            AddToDefCollection defenderShip
        Next intI
        
        For intI = 1 To txtHBDEF.Text
            Set defenderShip = New clsHB
            
            AddToDefCollection defenderShip
        Next intI
    End If
    
    Set defenderShip = Nothing
End Sub

Private Sub ComposeAttacker()
    Dim attackerShip As clsShip
    Dim intI As Integer
    
    'Standard Ships
    'Attacker
    
    For intI = 1 To txtAegis.Text
        Set attackerShip = New clsAegis
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtScout.Text
        Set attackerShip = New clsScout
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtDRScout.Text
        Set attackerShip = New clsDR
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtFrigate.Text
        Set attackerShip = New clsFrigate
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtImpFrigate.Text
        Set attackerShip = New clsImpFrig
        
        AddToAttCollection attackerShip
    Next intI
    
     For intI = 1 To txtFSD.Text
        Set attackerShip = New clsFSD
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtDestroyer.Text
        Set attackerShip = New clsDestroyer
       
        AddToAttCollection attackerShip
    Next intI
     
    For intI = 1 To txtCruiser.Text
        Set attackerShip = New clsCruiser
        
        AddToAttCollection attackerShip
    Next intI
     
    For intI = 1 To txtHCruiser.Text
        Set attackerShip = New clsHCruiser
        
        AddToAttCollection attackerShip
    Next intI

    For intI = 1 To txtBats.Text
        Set attackerShip = New clsBattleship
       
        AddToAttCollection attackerShip
    Next intI
     
    For intI = 1 To txtDreads.Text
        Set attackerShip = New clsDread
        
        AddToAttCollection attackerShip
    Next intI
     
    For intI = 1 To txtLC.Text
        Set attackerShip = New clsLC
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtHC.Text
        Set attackerShip = New clsHC
        
        AddToAttCollection attackerShip
    Next intI
    
    'Special Ships
    
    For intI = 1 To txtAnvil.Text
        Set attackerShip = New clsAnvil
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtAsp.Text
        Set attackerShip = New clsAsp
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtAvalanche.Text
        Set attackerShip = New clsAvalanche
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtBadger.Text
        Set attackerShip = New clsBadger
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtBarracuda.Text
        Set attackerShip = New clsBarracuda
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtBerzerker.Text
        Set attackerShip = New clsBerzerker
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtCollector.Text
        Set attackerShip = New clsCollector
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtColossus.Text
        Set attackerShip = New clsColossus
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtCrusader.Text
        Set attackerShip = New clsCrusader
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtHammer.Text
        Set attackerShip = New clsHammer
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtHurricane.Text
        Set attackerShip = New clsHurricane
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtInterdictor.Text
        Set attackerShip = New clsInterdictor
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtJudicator.Text
        Set attackerShip = New clsJudic
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtLeopard.Text
        Set attackerShip = New clsLeopard
        
        AddToAttCollection attackerShip
    Next intI

    For intI = 1 To txtOrca.Text
        Set attackerShip = New clsOrca
       
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtPrivateer.Text
        Set attackerShip = New clsPrivateer
        
        AddToAttCollection attackerShip
    Next intI
   
    For intI = 1 To txtRaven.Text
        Set attackerShip = New clsRaven
        
        AddToAttCollection attackerShip
    Next intI
       
    For intI = 1 To txtTangler.Text
        Set attackerShip = New clsTangler
        
        AddToAttCollection attackerShip
    Next intI
        
    For intI = 1 To txtTerrapin.Text
        Set attackerShip = New clsTerrapin
        
        AddToAttCollection attackerShip
    Next intI
        
    For intI = 1 To txtVespa.Text
        Set attackerShip = New clsVespa
        
        AddToAttCollection attackerShip
    Next intI
        
    For intI = 1 To txtWayfarer.Text
        Set attackerShip = New clsWayfarer
        
        AddToAttCollection attackerShip
    Next intI
        
    For intI = 1 To txtZephyer.Text
        Set attackerShip = New clsZephyr
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtGoliath.Text
        Set attackerShip = New clsGoliath
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtTortoise.Text
        Set attackerShip = New clsTor

        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtDragon.Text
        Set attackerShip = New clsDragon
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtSandstorm.Text
        Set attackerShip = New clsSandstorm
        
        AddToAttCollection attackerShip
    Next intI
    
    For intI = 1 To txtMaelstrom.Text
        Set attackerShip = New clsMaelstrom
        
        AddToAttCollection attackerShip
    Next intI
    
    Set attackerShip = Nothing
End Sub

Private Sub AddStructures(Optional blnFront As Boolean = False)
    'Structures that sit behind fleet
    'Jumpgates etc
    Dim defenderShip As clsShip
    Dim intI As Integer
    
    For intI = 1 To txtJumpgate.Text
        Set defenderShip = New clsJumpgate
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtImpJumpgate.Text
        Set defenderShip = New clsImpJumpgate

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtFolder.Text
        Set defenderShip = New clsFolder

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtOCY.Text
        Set defenderShip = New clsOCY

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPOCY.Text
        Set defenderShip = New clsImpOCY
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtSat.Text
        Set defenderShip = New clsSat
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtImpSat.Text
        Set defenderShip = New clsImpSat
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    Set defenderShip = Nothing
End Sub
Private Sub AddDefendingStructures(Optional blnFront As Boolean = False)
    'Structures that come first e.g. starbases shields and odp's and stuff
    Dim defenderShip As clsShip
    Dim intI As Integer
    
    For intI = 1 To txtODPDEF.Text
        Set defenderShip = New clsODP

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPODPDEF.Text
        Set defenderShip = New clsImpODP

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtODMDEF.Text
        Set defenderShip = New clsODM

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtStarbaseDEF.Text
        Set defenderShip = New clsStarbase

        AddToDefCollection defenderShip, blnFront
    Next intI

    For intI = 1 To txtOSDEF.Text
        Set defenderShip = New clsShield

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPOSDEF.Text
        Set defenderShip = New clsImpShield

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtOBDEF.Text
        Set defenderShip = New clsBulwark

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    Set defenderShip = Nothing
End Sub

Private Sub AddSurfaceStructures(Optional blnFront As Boolean = False)
    'Structures that come first e.g. starbases shields and odp's and stuff
    Dim defenderShip As clsShip
    Dim intI As Integer
    
    For intI = 1 To txtTurret.Text
        Set defenderShip = New clsTurret

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtSDB.Text
        Set defenderShip = New clsSDB

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPSDB.Text
        Set defenderShip = New clsImpSDB

        AddToDefCollection defenderShip, blnFront
    Next intI

    For intI = 1 To txtMonolith.Text
        Set defenderShip = New clsMonolith

        AddToDefCollection defenderShip, blnFront
    Next intI
    
     For intI = 1 To txtSSG.Text
        Set defenderShip = New clsSSG

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPSSG.Text
        Set defenderShip = New clsImpSSG
        
        AddToDefCollection defenderShip, blnFront
    Next intI

'    For intI = 1 To txtMonolith.Text
'        Set defenderShip = New clsMonolith
'
'        AddToDefCollection defenderShip, blnFront
'    Next intI
    
    Set defenderShip = Nothing
End Sub

Private Sub ComposeDefender()
    Dim defenderShip As clsShip
    Dim intI As Integer

    'Defender
    For intI = 1 To txtScoutDEF.Text
        Set defenderShip = New clsScout

        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtDRScoutDEF.Text
        Set defenderShip = New clsDR

        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtFrigateDEF.Text
        Set defenderShip = New clsFrigate
        
        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtImpFrigateDEF.Text
        Set defenderShip = New clsImpFrig

        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtFSDDEF.Text
        Set defenderShip = New clsFSD
 
        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtDestroyerDEF.Text
        Set defenderShip = New clsDestroyer

        AddToDefCollection defenderShip
    Next intI
         
    For intI = 1 To txtCruiserDEF.Text
        Set defenderShip = New clsCruiser

        AddToDefCollection defenderShip
    Next intI
     
    For intI = 1 To txtHCruiserDEF.Text
        Set defenderShip = New clsHCruiser
 
        AddToDefCollection defenderShip
    Next intI
     
    For intI = 1 To txtLCDEF.Text
        Set defenderShip = New clsLC

        AddToDefCollection defenderShip
    Next intI
     
    For intI = 1 To txtBatsDEF.Text
        Set defenderShip = New clsBattleship

        AddToDefCollection defenderShip
    Next intI
     
    For intI = 1 To txtDreadsDEF.Text
        Set defenderShip = New clsDread

        AddToDefCollection defenderShip
    Next intI
     
    For intI = 1 To txtHCDEF.Text
        Set defenderShip = New clsHC
        
        AddToDefCollection defenderShip
    Next intI
    
    'Special Ships Defender
    For intI = 1 To txtSandstormDEF.Text
        Set defenderShip = New clsSandstorm
        
        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtMaelstromDEF.Text
        Set defenderShip = New clsMaelstrom
        
        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtAegisDEF.Text
        Set defenderShip = New clsAegis
       
        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtAnvilDEF.Text
        Set defenderShip = New clsAnvil

        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtAspDEF.Text
        Set defenderShip = New clsAsp

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtAvalancheDEF.Text
        Set defenderShip = New clsAvalanche
       
        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtBadgerDEF.Text
        Set defenderShip = New clsBadger
      
        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtBarracudaDEF.Text
        Set defenderShip = New clsBarracuda

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtBerzerkerDEF.Text
        Set defenderShip = New clsBerzerker

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtCollectorDEF.Text
        Set defenderShip = New clsCollector

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtColossusDEF.Text
        Set defenderShip = New clsColossus

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtCrusaderDEF.Text
        Set defenderShip = New clsCrusader

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtDragonDEF.Text
        Set defenderShip = New clsDragon

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtGoliathDEF.Text
        Set defenderShip = New clsGoliath
   
        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtHammerDEF.Text
        Set defenderShip = New clsHammer

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtHurricaneDEF.Text
        Set defenderShip = New clsHurricane

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtInterdictorDEF.Text
        Set defenderShip = New clsInterdictor
 
        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtJudicatorDEF.Text
        Set defenderShip = New clsJudic
   
        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtLeopardDEF.Text
        Set defenderShip = New clsLeopard
       
        AddToDefCollection defenderShip
    Next intI

    For intI = 1 To txtOrcaDEF.Text
        Set defenderShip = New clsOrca

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtPrivateerDEF.Text
        Set defenderShip = New clsPrivateer

        AddToDefCollection defenderShip
    Next intI
   
    For intI = 1 To txtRavenDEF.Text
        Set defenderShip = New clsRaven

        AddToDefCollection defenderShip
    Next intI

    For intI = 1 To txtTanglerDEF.Text
        Set defenderShip = New clsTangler
    
        AddToDefCollection defenderShip
    Next intI
        
    For intI = 1 To txtTerrapinDEF.Text
        Set defenderShip = New clsTerrapin

        AddToDefCollection defenderShip
    Next intI
        
    For intI = 1 To txtVespaDEF.Text
        Set defenderShip = New clsVespa

        AddToDefCollection defenderShip
    Next intI
        
    For intI = 1 To txtWayfarerDEF.Text
        Set defenderShip = New clsWayfarer

        AddToDefCollection defenderShip
    Next intI
        
    For intI = 1 To txtZephyerDEF.Text
        Set defenderShip = New clsZephyr

        AddToDefCollection defenderShip
    Next intI
    
    For intI = 1 To txtTortoiseDEF.Text
        Set defenderShip = New clsTor

        AddToDefCollection defenderShip
    Next intI
    
    Set defenderShip = Nothing
End Sub

Private Sub ResetWeapons()
    Dim intI As Integer
    Dim Ship As clsShip
    
    For intI = 1 To colAttacker.Count
        Set Ship = colAttacker.Item(intI, True)
        Ship.ResetWeapons
    Next
    
    For intI = 1 To colDefender.Count
        Set Ship = colDefender.Item(intI, False)
        Ship.ResetWeapons
    Next
    
    Set Ship = Nothing
End Sub

Private Function GetValidShip(ByVal colCollection As Collection, ByRef intShipNumber As Integer, blnAttack As Boolean) As clsShip
    Dim intColCount As Integer
    intColCount = colCollection.Count
    
    While intShipNumber <= intColCount
        Set GetValidShip = colCollection.Item(intShipNumber)
        
        If Not GetValidShip.Destroyed Then
            'We got a valid ship so exit
            
             If (blnAttack) Then
                'Check if there are weapons left
                If (GetValidShip.WeaponsLeft > 0) Then
                    'We got a valid ship so exit
                    Exit Function
                Else
                    'Get next ship and check if it's valid
                    intShipNumber = intShipNumber + 1
                End If
            Else
                Exit Function
            End If
        Else
            'Get next ship and check if it's valid
            intShipNumber = intShipNumber + 1
        End If
    Wend

End Function

'Private Function GetValidShip2(ByVal colCollection As colShips, ByRef intShipNumber As Integer, blnAttack As Boolean, intTypeNbr As Integer, strPreviousShip As String) As clsShip
'    Dim intColCount As Integer
'    intColCount = colCollection.Count
'   ' Dim strPreviousShip As String
'
'   ' strPreviousShip = colCollection.Item(intShipNumber).Name
'
'    While intShipNumber <= intColCount
'        Set GetValidShip2 = colCollection.Item(intShipNumber)
'        intTypeNbr = colCollection.ShipTypeNbr
'
'       ' If (blnAttack) Then
''            If (strPreviousShip <> GetValidShip2.Name) Then
''                intTypeNbr = 1
''            Else
''                intTypeNbr = intTypeNbr + 1
''            End If
'     '   End If
'
'        If Not GetValidShip2.Destroyed Then
'            If (blnAttack) Then
'               'Check if there are weapons left
'               If (GetValidShip2.WeaponsLeft > 0) Then
'                   'We got a valid ship so exit
'                   Exit Function
'               Else
''                    If (strPreviousShip <> GetValidShip2.Name) Then
''                        intTypeNbr = 1
''                    Else
''                        intTypeNbr = intTypeNbr + 1
''                    End If
'
'                    'Get next ship and check if it's valid
'                    intShipNumber = intShipNumber + 1
'               End If
'            Else
'               Exit Function
'            End If
'        Else
'            'Get next ship and check if it's valid
''            If (strPreviousShip <> GetValidShip2.Name) Then
''                intTypeNbr = 1
''            Else
''                intTypeNbr = intTypeNbr + 1
''            End If
'
'            intShipNumber = intShipNumber + 1
'        End If
'    Wend
'
'End Function

Private Function GetValidShipAttacker(ByVal colCollection As colShipsAttacker, ByRef intShipNumber As Integer, blnAttack As Boolean, intTypeNbr As Integer, strPreviousShip As String) As clsShip
    Dim intColCount As Integer
    intColCount = colCollection.Count
   ' Dim strPreviousShip As String
    
   ' strPreviousShip = colCollection.Item(intShipNumber).Name
    
    While intShipNumber <= intColCount
        Set GetValidShipAttacker = colCollection.Item(intShipNumber, blnAttack)
        intTypeNbr = colCollection.ShipTypeNbr(blnAttack)
        
       ' If (blnAttack) Then
'            If (strPreviousShip <> GetValidShip2.Name) Then
'                intTypeNbr = 1
'            Else
'                intTypeNbr = intTypeNbr + 1
'            End If
     '   End If
        
        If Not GetValidShipAttacker.Destroyed Then
            If (blnAttack) Then
               'Check if there are weapons left
               If (GetValidShipAttacker.WeaponsLeft > 0) Then
                   'We got a valid ship so exit
                   Exit Function
               Else
'                    If (strPreviousShip <> GetValidShip2.Name) Then
'                        intTypeNbr = 1
'                    Else
'                        intTypeNbr = intTypeNbr + 1
'                    End If
                    
                    'Get next ship and check if it's valid
                    intShipNumber = intShipNumber + 1
               End If
            Else
               Exit Function
            End If
        Else
            'Get next ship and check if it's valid
'            If (strPreviousShip <> GetValidShip2.Name) Then
'                intTypeNbr = 1
'            Else
'                intTypeNbr = intTypeNbr + 1
'            End If
                    
            intShipNumber = intShipNumber + 1
        End If
    Wend

End Function

Private Function GetValidShipDefender(ByVal colCollection As colShipsDefender, ByRef intShipNumber As Integer, blnAttack As Boolean, intTypeNbr As Integer, strPreviousShip As String) As clsShip
    Dim intColCount As Integer
    intColCount = colCollection.Count
   ' Dim strPreviousShip As String
    
   ' strPreviousShip = colCollection.Item(intShipNumber).Name
    
    While intShipNumber <= intColCount
        Set GetValidShipDefender = colCollection.Item(intShipNumber, blnAttack)
        intTypeNbr = colCollection.ShipTypeNbr(blnAttack)
        
       ' If (blnAttack) Then
'            If (strPreviousShip <> GetValidShip2.Name) Then
'                intTypeNbr = 1
'            Else
'                intTypeNbr = intTypeNbr + 1
'            End If
     '   End If
        
        If Not GetValidShipDefender.Destroyed Then
            If (blnAttack) Then
               'Check if there are weapons left
               If (GetValidShipDefender.WeaponsLeft > 0) Then
                   'We got a valid ship so exit
                   Exit Function
               Else
'                    If (strPreviousShip <> GetValidShip2.Name) Then
'                        intTypeNbr = 1
'                    Else
'                        intTypeNbr = intTypeNbr + 1
'                    End If
                    
                    'Get next ship and check if it's valid
                    intShipNumber = intShipNumber + 1
               End If
            Else
               Exit Function
            End If
        Else
            'Get next ship and check if it's valid
'            If (strPreviousShip <> GetValidShip2.Name) Then
'                intTypeNbr = 1
'            Else
'                intTypeNbr = intTypeNbr + 1
'            End If
                    
            intShipNumber = intShipNumber + 1
        End If
    Wend

End Function
Private Function DBprintAtt(attackerShipShooting As clsShip, intAttackerShipShooting As Integer)
    If DEV Then
        If Not attackerShipShooting Is Nothing Then
            Debug.Print "Attacker: #" & intAttackerShipShooting
            Debug.Print "Name :" & attackerShipShooting.Name
            Debug.Print "Ships :" & attackerShipShooting.Ships
            Debug.Print "ShipsLeft :" & attackerShipShooting.ShipsLeft
            'Debug.Print "ShipsShootingLeft :" & attackerShipShooting.ShipsShootingLeft
            Debug.Print "ShipsWeapons :" & attackerShipShooting.Weapons
            Debug.Print "ShipsWeaponsLeft :" & attackerShipShooting.WeaponsLeft
            Debug.Print "Durability :" & attackerShipShooting.Durability & vbCrLf
        End If
    End If
End Function

Private Function DBprintDef(defendershipshooting As clsShip, intDefenderShipShooting As Integer)
    If DEV Then
        If Not defendershipshooting Is Nothing Then
            Debug.Print "Defender: #" & intDefenderShipShooting
            Debug.Print "Name :" & defendershipshooting.Name
            Debug.Print "Ships :" & defendershipshooting.Ships
            Debug.Print "ShipsLeft :" & defendershipshooting.ShipsLeft
            'Debug.Print "ShipsShootingLeft :" & defenderShipShooting.ShipsShootingLeft
            Debug.Print "ShipsWeapons :" & defendershipshooting.Weapons
            Debug.Print "ShipsWeaponsLeft :" & defendershipshooting.WeaponsLeft
            Debug.Print "Durability :" & defendershipshooting.Durability & vbCrLf
        End If
    End If
End Function

Private Function BattleResult(objAttacker As clsShip, objDefender As clsShip) As Single
    Dim sngBaseDamage As Single
    Dim sngResult As Single

    If cmbFormula.ListIndex = 0 Then
        'Base formula
    
        Dim sngRand1 As Single
        Dim sngRand2 As Single
    
        If (objAttacker.ShipType = 0) Then
            'Attacker is a Capital ship
            
            'Determine random number based on sizerank
            If (objDefender.SizeRank > 30) Then
                'Higher change to hit
                'TODO check and remove drone code
                If (objAttacker.Name = "Stinger Drone") Then
                    sngRand1 = Rand(0.1, 0.45)
                Else
                    sngRand1 = Rand(0.4, 0.8)
                End If
            Else
                sngRand1 = Rand(0.1, 0.45)
            End If
            
            'Use the correct offense defense values
            If (objDefender.ShipType = 0) Then 'Capital
                sngBaseDamage = objAttacker.OffCap / objDefender.DefCap
                sngResult = sngBaseDamage * objAttacker.OffCap * sngRand1
            ElseIf (objDefender.ShipType = 1) Then 'fighter
                sngBaseDamage = objAttacker.OffFight / objDefender.DefCap
                sngResult = sngBaseDamage * objAttacker.OffCap * sngRand1
            ElseIf (objDefender.ShipType = 2) Then 'structure
                sngBaseDamage = objAttacker.OffStruct / objDefender.DefCap
                sngResult = sngBaseDamage * objAttacker.OffCap * sngRand1
            End If
            
        ElseIf (objAttacker.ShipType = 1) Then
            'Attacker is a Fighter
            
            'Determine random number based on sizerank
            If (objDefender.ShipType = 0) Then 'Capital
                'Determine random number based on sizerank
                If (objDefender.SizeRank > 30) Then
                    'Higher change to hit
                    sngRand1 = Rand(2.2, 5)
                Else
                    sngRand1 = Rand(0.4, 2) '0.4
                End If
            Else
                sngRand1 = Rand(0.1, 1)
            End If
            
            If (objDefender.ShipType = 0) Then 'Capital
                'Defender is a capital ship
                sngBaseDamage = objAttacker.OffCap / objDefender.DefFight
                sngResult = sngBaseDamage * objAttacker.OffCap * sngRand1
            ElseIf (objDefender.ShipType = 1) Then
                'Defender is a fighter
                sngBaseDamage = objAttacker.OffFight / objDefender.DefFight
                sngResult = sngBaseDamage * objAttacker.OffCap * sngRand1
            ElseIf (objDefender.ShipType = 2) Then
                'Defender is a structure
                sngBaseDamage = objAttacker.OffStruct / objDefender.DefFight
                sngResult = sngBaseDamage * objAttacker.OffCap * sngRand1
            End If
        ElseIf (objAttacker.ShipType = 2) Then
            'Attacker is a defending structure
            
            'Random number
            sngRand1 = Rand(0.1, 0.8)
            
            If (objDefender.ShipType = 0) Then 'Capital
                sngBaseDamage = objAttacker.OffCap / objDefender.DefCap
                sngResult = sngBaseDamage * objAttacker.OffCap * sngRand1
            ElseIf (objDefender.ShipType = 1) Then 'fighter
                sngBaseDamage = objAttacker.OffFight / objDefender.DefCap
                sngResult = sngBaseDamage * objAttacker.OffCap * sngRand1
            End If
        End If
       
        BattleResult = CInt(sngResult)
    
        If BattleResult = 0 Then
            '20% rule TODO
            
            BattleResult = CInt(Rand(0.1, 0.9))
        End If
        
        'Reduce Durability
        objDefender.Durability = objDefender.Durability - BattleResult
    Else
        Dim sngAdditionalDamageRatio As Single
        Dim sngOffense As Single
        Dim sngDefense As Single
               
        'Metallikovs formula
        
        'Determine sngOffense, sngDefense
        If (objAttacker.ShipType = 0) Then
            'Capital ship
            If (objDefender.ShipType = 0) Then 'Capital
                sngOffense = objAttacker.OffCap
                sngDefense = objDefender.DefCap
            ElseIf (objDefender.ShipType = 1) Then 'fighter
                sngOffense = objAttacker.OffFight
                sngDefense = objDefender.DefCap
            ElseIf (objDefender.ShipType = 2) Then 'structure
                sngOffense = objAttacker.OffStruct
                sngDefense = objDefender.DefCap
            End If
        ElseIf (objAttacker.ShipType = 1) Then
            'Attacker is a Fighter
            If (objDefender.ShipType = 0) Then 'Capital
                sngOffense = objAttacker.OffCap
                sngDefense = objDefender.DefFight
            ElseIf (objDefender.ShipType = 1) Then 'fighter
                sngOffense = objAttacker.OffFight
                sngDefense = objDefender.DefFight
            ElseIf (objDefender.ShipType = 2) Then 'structure
                sngOffense = objAttacker.OffStruct
                sngDefense = objDefender.DefCap
            End If
        
        ElseIf (objAttacker.ShipType = 2) Then
            'Attacker is a Strucure
            If (objDefender.ShipType = 0) Then 'Capital
                sngOffense = objAttacker.OffStruct
                sngDefense = objDefender.DefCap
            ElseIf (objDefender.ShipType = 1) Then 'fighter
                sngOffense = objAttacker.OffStruct
                sngDefense = objDefender.DefCap
            End If
        End If
        
        If (objAttacker.OffCap * 1.2 >= objDefender.DefCap) Then
            sngBaseDamage = objAttacker.OffCap - objDefender.DefCap
            
            If sngBaseDamage < 0 Then
                sngBaseDamage = 0
            End If
            
            sngAdditionalDamageRatio = objDefender.DefCap / objAttacker.OffCap
            
            If sngAdditionalDamageRatio > 1 Then
                sngAdditionalDamageRatio = 1
            End If
            
            sngResult = sngBaseDamage + (sngRandomfactor * (sngAdditionalDamageRatio ^ 6))
            
            If sngResult < 1 Then
                sngResult = 1
            End If
            
        ElseIf (objAttacker.OffCap * 1.2 < objDefender.DefCap) Then
            sngResult = 1
            
            If (objAttacker.OffCap < (objDefender.DefCap / sngCriticalHitRatio)) Then
                sngResult = 0.2
            End If
        End If
    
        BattleResult = sngResult
        
        'Reduce Durability
        objDefender.Durability = objDefender.Durability - BattleResult
    End If
   
End Function

Private Function BattleEngine()
    'Do some magic ;)
    Dim intAttackerShipShot As Integer
    Dim intDefenderShipShot As Integer
    Dim intDefenderShipShooting As Integer
    Dim intAttackerShipShooting As Integer
    Dim attackerShipShot As clsShip
    Dim defenderShipShot As clsShip
    Dim attackerShipShooting As clsShip
    Dim defendershipshooting As clsShip
    Dim strPreviousShip As String
    Dim intAttackerShipNbr As Integer
    Dim intDefenderShipNbr As Integer
    Dim intTotalDefenderShip As Integer
    Dim intTotalAttackerShip As Integer
    Dim sngOff As Single
    Dim blnEndLoop As Boolean
    Dim intTotalvolleys As Integer
    Dim intvolley As Integer
    
    If Dir(App.Path & "\battle.txt") <> "" Then
        Kill App.Path & "\battle.txt"
    End If
    
    lstResult.Clear
    
    If colAttacker.Count = 0 Or colDefender.Count = 0 Then
        Exit Function
    End If
    'Fight a battle
    'Set the number of volleys
    intTotalvolleys = txtVolleys.Text
    
    'first volley
    For intvolley = 1 To intTotalvolleys
        'Reset all Weapons before a new volley starts
        AddListItem " "
        AddListItem "Volley number " & intvolley & ": "
        
        If intvolley > 1 Then
            ResetWeapons
        End If
        
        intDefenderShipShot = 1
        intDefenderShipShooting = 1
        intAttackerShipShot = 1
        intAttackerShipShooting = 1
        
        intAttackerShipNbr = 1
        intDefenderShipNbr = 1
        intTotalDefenderShip = colDefender.Count
        intTotalAttackerShip = colAttacker.Count
        
        'First we loop all attacker ships a attacker always starts the battle
        For intAttackerShipShooting = 1 To colAttacker.Count
            blnEndLoop = False
            
            'First ship attacks
            
            If Not attackerShipShooting Is Nothing Then
                strPreviousShip = attackerShipShooting.Name
            Else
                strPreviousShip = ""
            End If
            
            Set attackerShipShooting = GetValidShip(colAttacker, intAttackerShipShooting, True)
            Set attackerShipShot = GetValidShip(colAttacker, intAttackerShipShot, False)
            
            If strPreviousShip <> attackerShipShooting.Name Then
                'if we got a different ship type then before we reset our attacker ship number (used in logging)
                intAttackerShipNbr = 1
            End If
            
            'Loop until attacker ship is out of weapons
            Do Until blnEndLoop = True
            
                If (intAttackerShipShooting >= intTotalAttackerShip) Then
                    If attackerShipShooting.Destroyed Then
                        'Attacker is out of ships
                         Exit Do
                    End If
                End If

                If Not defendershipshooting Is Nothing Then
                    strPreviousShip = defendershipshooting.Name
                Else
                    strPreviousShip = ""
                End If
                
                Set defenderShipShot = GetValidShip(colDefender, intDefenderShipShot, False)
                Set defendershipshooting = GetValidShip(colDefender, intDefenderShipShooting, True)
                
                If Not defendershipshooting Is Nothing Then
                    If strPreviousShip <> defendershipshooting.Name Then
                        'if we got a different ship type then before we reset our defender ship number (used in logging)
                        intDefenderShipNbr = 1
                    End If
                End If

                If (intDefenderShipShooting <= intTotalDefenderShip) Then
                    'continue
                Else
                    'defender is out of ships that can shoot back
                    'Exit For
                    'AddListItem "Defender out of shooting ships"
                    If (intDefenderShipShot > intTotalDefenderShip) Then
                        'Out of ships
                        blnEndLoop = True
                        Set defendershipshooting = Nothing
                        Exit Do
                    End If
                End If
                    
                'The actual BATTLE
                'Attackership shoots
                DBprintAtt attackerShipShooting, intAttackerShipShooting
                DBprintDef defenderShipShot, intDefenderShipShot
                
                sngOff = BattleResult(attackerShipShooting, defenderShipShot)
                
                'Add to our list
                If (sngOff <= 0) Then
                    colAttacker.AddMiss
                    AddListItem "Attacker: " & attackerShipShooting.Name & " (" & intAttackerShipNbr & ") fired at " & defenderShipShot.Name & " (" & intDefenderShipNbr & ") but missed."
                Else
                    colAttacker.AddHit
                    
                    If (defenderShipShot.Durability > 0) Then
                        AddListItem "Attacker: " & attackerShipShooting.Name & " (" & intAttackerShipNbr & ") fired at " & defenderShipShot.Name & " (" & intDefenderShipNbr & ") and hit it, doing " & sngOff & " damage, Remaining hull strength is " & defenderShipShot.Durability & "."
                    Else
                        AddListItem "Attacker: " & attackerShipShooting.Name & " (" & intAttackerShipNbr & ") fired at " & defenderShipShot.Name & " (" & intDefenderShipNbr & ") and hit it for " & sngOff & " damage, destroying it!"
                    End If
                End If
                
                'Reduce attacker weapon because he fired a shot
                attackerShipShooting.SubstractWeapon
                
                DBprintAtt attackerShipShooting, intAttackerShipNbr
                DBprintDef defenderShipShot, intDefenderShipNbr
                 
                If Not defenderShipShot Is Nothing Then
                    If defenderShipShot.Destroyed Then
                        'Ship is destroyed take next ship
                        'AddListItem "Defender: " & defenderShipShot.Name & " (" & intDefenderShipNbr & ") ship destroyed"
                         
                        If (intDefenderShipShot = intTotalDefenderShip) Then
                            Exit For 'Battle ends defender lost all ships
                        Else
                            'take next ship
                            
                            'if this ship was the defending shooting ship get the next one because this one is destroyed
                            If intDefenderShipShot = intDefenderShipShooting Then
                                'Increase all defending counters
                                intDefenderShipNbr = intDefenderShipNbr + 1
                                intDefenderShipShot = intDefenderShipShot + 1
                                intDefenderShipShooting = intDefenderShipShooting + 1
                                
                                'Check if we still got defending ships if we have one select it
                                If (intDefenderShipShooting <= intTotalDefenderShip) Then
                                    Set defendershipshooting = GetValidShip(colDefender, intDefenderShipShooting, True)
                                Else
                                    'Battle ends defender is out of shooting ships
                                    Exit For
                                End If
                            Else
                                intDefenderShipShot = intDefenderShipShot + 1
                                intDefenderShipNbr = intDefenderShipNbr + 1
                            End If
    
                            'Get next ship
                            Set defenderShipShot = GetValidShip(colDefender, intDefenderShipShot, False)
                        End If
                    End If
                End If
                
                If Not defendershipshooting Is Nothing Then
                   If Not (defendershipshooting.WeaponsLeft > 0) Then
                        'Get next defender ship that can fire a weapon
                        intDefenderShipShooting = intDefenderShipShooting + 1
                        intDefenderShipNbr = intDefenderShipNbr + 1
                        
                        If (intDefenderShipShooting <= intTotalDefenderShip) Then
                            If Not defendershipshooting Is Nothing Then
                                strPreviousShip = defendershipshooting.Name
                            Else
                                strPreviousShip = ""
                            End If
                            
                            Set defendershipshooting = GetValidShip(colDefender, intDefenderShipShooting, True)
                            
                            If strPreviousShip <> defendershipshooting.Name Then
                                intDefenderShipNbr = 1
                            End If
                            
                            DroneBattle defendershipshooting, attackerShipShot, blnEndLoop, intDefenderShipNbr, intAttackerShipNbr, intDefenderShipShot, intDefenderShipShooting, intAttackerShipShot, defenderShipShot, intTotalDefenderShip
                        Else
                            'defender is out of ships that can shoot back
                            'Exit For
                            AddListItem "Defender out of shooting ships"
                            intDefenderShipShooting = intDefenderShipShooting - 1
                            intDefenderShipNbr = intDefenderShipNbr - 1
                            Set defendershipshooting = Nothing
                        End If
                    Else
                        DroneBattle defendershipshooting, attackerShipShot, blnEndLoop, intDefenderShipNbr, intAttackerShipNbr, intDefenderShipShot, intDefenderShipShooting, intAttackerShipShot, defenderShipShot, intTotalDefenderShip
                    End If
                End If
                
                If Not attackerShipShot.Destroyed Then
                    If (attackerShipShooting.WeaponsLeft = 0) Then
                        'intAttackerShipShooting = intAttackerShipShooting + 1
                        'AddListItem "Attacker: " & attackerShipShooting.Name & " (" & intAttackerShipNbr & ") ship out of weapons"
                        blnEndLoop = True
                    End If
                End If
            Loop
        Next
        
        AddListItem "DEFENDER"
        
        'Loop on the remaining Defender ships that still have weapons left to fire
        For intDefenderShipShooting = 1 To colDefender.Count
            intAttackerShipShot = 1
            
            Set defendershipshooting = GetValidShip(colDefender, intDefenderShipShooting, False)
            Set attackerShipShot = GetValidShip(colAttacker, intAttackerShipShot, False)
            blnEndLoop = False
            
            If (intAttackerShipShot > colAttacker.Count) Then
                AddListItem "Attacker out of ships"
                Exit For
            End If
            
            If (intDefenderShipShooting <= intTotalDefenderShip) Then
                'nothing
                Exit For
            Else
                'defender is out of ships that can shoot back
                'AddListItem "Defender out of shooting ships"
                Exit For
            End If

            'Defender ship shoots at remaining attacking ships until defender is out of weapons
            Do Until blnEndLoop = True
                If (defendershipshooting.WeaponsLeft > 0) Then
                    DroneBattle defendershipshooting, attackerShipShot, blnEndLoop, intDefenderShipNbr, intAttackerShipNbr, intDefenderShipShot, intDefenderShipShooting, intAttackerShipShot, defenderShipShot, intTotalDefenderShip
                Else
                    'Defender doesn't shoot back
                    'AddListItem "Defender: " & defenderShipShooting.Name & " (" & intDefenderShipShooting & ") ship out of weapons"
                    
                    'Get next defender ship that can fire a weapon
                    intDefenderShipShooting = intDefenderShipShooting + 1
                    'intDefenderShipNbr = intDefenderShipNbr + 1
                    
                    If (intDefenderShipShooting <= intTotalDefenderShip) Then
                        Set defendershipshooting = GetValidShip(colDefender, intDefenderShipShooting, False)
                    Else
                        'defender is out of ships that can shoot back
                        'Exit For
                        'AddListItem "Defender out of shooting ships"
                        blnEndLoop = True
                        Set defendershipshooting = Nothing
                    End If
                End If
            Loop
        Next
    Next
    
    'Battle is done calculate and post results
    CalculateResult colAttacker, colDefender
    
    Set colAttacker = Nothing
    Set colDefender = Nothing
    Set attackerShipShot = Nothing
    Set defenderShipShot = Nothing
    Set attackerShipShooting = Nothing
    Set defendershipshooting = Nothing
End Function

Private Function BattleEngine2()
    'Do some magic ;)
    Dim intAttackerShipShot As Integer
    Dim intDefenderShipShot As Integer
    Dim intDefenderShipShooting As Integer
    Dim intAttackerShipShooting As Integer
    Dim attackerShipShot As clsShip
    Dim defenderShipShot As clsShip
    Dim attackerShipShooting As clsShip
    Dim defendershipshooting As clsShip
    Dim strPreviousShip As String
    'Dim intAttackerShipNbr As Integer
    'Dim intDefenderShipNbr As Integer
    Dim intTotalDefenderShip As Integer
    Dim intTotalAttackerShip As Integer
    Dim sngOff As Single
    Dim blnEndLoop As Boolean
    Dim intTotalvolleys As Integer
    Dim intvolley As Integer
    Dim intAttShootingTypeNbr As Integer
    Dim intAttShotTypeNbr As Integer
    Dim intDefShootingTypeNbr As Integer
    Dim intDefShotTypeNbr As Integer
    
    'If Dir(App.Path & "\battle.txt") <> "" Then
    '    Kill App.Path & "\battle.txt"
    'End If
    
    lstResult.Clear
    
    If colAttacker.Count = 0 Or colDefender.Count = 0 Then
        Exit Function
    End If
    'Fight a battle
    'Set the number of volleys
    intTotalvolleys = txtVolleys.Text
    
    If cmbFormula.ListIndex = 0 Then
        'Base formula
        Log "", "Formula used: Base formula"
    Else
        Log "", "Formula used: Metallikovs formula"
    End If
    
    AddListItem "Attacker Off: " & intAttackerOffense & " Def: " & intAttackerDefense & " Dur: " & intAttackerDurability
    AddListItem "Defender Off: " & intDefenderOffense & " Def: " & intDefenderDefense & " Dur: " & intDefenderDurability
    AddListItem "Attacking Units: " & colAttacker.Count
    AddListItem "Defending Units: " & colDefender.Count
    
    'first volley
    For intvolley = 1 To intTotalvolleys
        If intvolley > 1 Then
            AddListItem " "
            AddListItem "Attacker Shots: " & colAttacker.GetHits & " (Total shots = " & (colAttacker.GetHits + colAttacker.GetMisses) & " Misses = " & colAttacker.GetMisses & ")"
            AddListItem "Defender Shots: " & colDefender.GetHits & " (Total shots = " & (colDefender.GetHits + colDefender.GetMisses) & " Misses = " & colDefender.GetMisses & ")"
        End If
                
        'Reset all Weapons before a new volley starts
        AddListItem " "
        AddListItem "Volley number " & intvolley & ": "
        
        colDefender.ResetCounters
        colDefender.ResetShots
        colAttacker.ResetCounters
        colAttacker.ResetShots
        
        If intvolley > 1 Then
            ResetWeapons
        End If
        
        intDefenderShipShot = 1
        intDefenderShipShooting = 1
        intAttackerShipShot = 1
        intAttackerShipShooting = 1
        
        intTotalDefenderShip = colDefender.Count
        intTotalAttackerShip = colAttacker.Count
        
        'First we loop all attacker ships a attacker always starts the battle
        For intAttackerShipShooting = 1 To colAttacker.Count
            blnEndLoop = False
            
            'First ship attacks
            If Not attackerShipShooting Is Nothing Then
                 Set attackerShipShooting = GetValidShipAttacker(colAttacker, intAttackerShipShooting, True, intAttShootingTypeNbr, attackerShipShooting.Name)
            Else
                 Set attackerShipShooting = GetValidShipAttacker(colAttacker, intAttackerShipShooting, True, intAttShootingTypeNbr, "")
            End If
            
            If Not attackerShipShot Is Nothing Then
                 Set attackerShipShot = GetValidShipAttacker(colAttacker, intAttackerShipShot, False, intAttShotTypeNbr, attackerShipShot.Name)
            Else
                 Set attackerShipShot = GetValidShipAttacker(colAttacker, intAttackerShipShot, False, intAttShotTypeNbr, "")
            End If
            
            'Loop until attacker ship is out of weapons
            Do Until blnEndLoop = True
            
                If (intAttackerShipShooting >= intTotalAttackerShip) Then
                    If attackerShipShooting.Destroyed Then
                        'Attacker is out of ships
                         Exit Do
                    End If
                End If
                
                If Not defenderShipShot Is Nothing Then
                    Set defenderShipShot = GetValidShipDefender(colDefender, intDefenderShipShot, False, intDefShotTypeNbr, defenderShipShot.Name)
                Else
                    Set defenderShipShot = GetValidShipDefender(colDefender, intDefenderShipShot, False, intDefShotTypeNbr, "")
                End If
                
                If Not defendershipshooting Is Nothing Then
                    Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, defendershipshooting.Name)
                Else
                    Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, "")
                End If

                If (intDefenderShipShooting <= intTotalDefenderShip) Then
                    'continue
                Else
                    'defender is out of ships that can shoot back
                    'Exit For
                    'AddListItem "Defender out of shooting ships"
                    If (intDefenderShipShot > intTotalDefenderShip) Then
                        'Out of ships
                        blnEndLoop = True
                        Set defendershipshooting = Nothing
                        Exit Do
                    End If
                End If
                    
                'The actual BATTLE
                'Attackership shoots
                DBprintAtt attackerShipShooting, intAttackerShipShooting
                DBprintDef defenderShipShot, intDefenderShipShot
                
                sngOff = BattleResult(attackerShipShooting, defenderShipShot)
                
                'Add to our list
                If (sngOff <= 0) Then
                    colAttacker.AddMiss
                    AddListItem "Attacker: " & attackerShipShooting.Name & " (" & intAttShootingTypeNbr & ") fired at " & defenderShipShot.Name & " (" & intDefShotTypeNbr & ") but missed."
                Else
                    colAttacker.AddHit
                    If (defenderShipShot.Durability > 0) Then
                        AddListItem "Attacker: " & attackerShipShooting.Name & " (" & intAttShootingTypeNbr & ") fired at " & defenderShipShot.Name & " (" & intDefShotTypeNbr & ") and hit it, doing " & sngOff & " damage, Remaining hull strength is " & defenderShipShot.Durability & "."
                    Else
                        AddListItem "Attacker: " & attackerShipShooting.Name & " (" & intAttShootingTypeNbr & ") fired at " & defenderShipShot.Name & " (" & intDefShotTypeNbr & ") and hit it for " & sngOff & " damage, destroying it!"
                    End If
                End If
                
                'Reduce attacker weapon because he fired a shot
                attackerShipShooting.SubstractWeapon
                
                DBprintAtt attackerShipShooting, intAttackerShipShooting
                DBprintDef defenderShipShot, intDefenderShipShot
                 
                If Not defenderShipShot Is Nothing Then
                    If defenderShipShot.Destroyed Then
                        'Ship is destroyed take next ship
                        'AddListItem "Defender: " & defenderShipShot.Name & " (" & intDefenderShipNbr & ") ship destroyed"
                         
                        If (intDefenderShipShot = intTotalDefenderShip) Then
                            Exit For 'Battle ends defender lost all ships
                        Else
                            'take next ship
                            
                            'if this ship was the defending shooting ship get the next one because this one is destroyed
                            If intDefenderShipShot = intDefenderShipShooting Then
                                'Increase all defending counters
                                intDefenderShipShot = intDefenderShipShot + 1
                                intDefenderShipShooting = intDefenderShipShooting + 1
                                
                                'Check if we still got defending ships if we have one select it
                                If (intDefenderShipShooting <= intTotalDefenderShip) Then
                                    If Not defendershipshooting Is Nothing Then
                                        Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, defendershipshooting.Name)
                                    Else
                                        Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, "")
                                    End If
                                Else
                                    'Battle ends defender is out of shooting ships
                                    Exit For
                                End If
                            Else
                                intDefenderShipShot = intDefenderShipShot + 1
                            End If
    
                            'Get next ship
                            If Not defenderShipShot Is Nothing Then
                                Set defenderShipShot = GetValidShipDefender(colDefender, intDefenderShipShot, False, intDefShotTypeNbr, defenderShipShot.Name)
                            Else
                                Set defenderShipShot = GetValidShipDefender(colDefender, intDefenderShipShot, False, intDefShotTypeNbr, "")
                            End If
                        End If
                    End If
                End If
                
                If Not defendershipshooting Is Nothing Then
                   If Not (defendershipshooting.WeaponsLeft > 0) Then
                        'Get next defender ship that can fire a weapon
                        intDefenderShipShooting = intDefenderShipShooting + 1
                        
                        If (intDefenderShipShooting <= intTotalDefenderShip) Then
                           
                            If Not defendershipshooting Is Nothing Then
                                Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, defendershipshooting.Name)
                            Else
                                Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, "")
                            End If
                          
                            DroneBattle2 defendershipshooting, attackerShipShot, blnEndLoop, intDefShootingTypeNbr, intAttShotTypeNbr, intDefenderShipShot, intDefenderShipShooting, intAttackerShipShot, defenderShipShot, intTotalDefenderShip, intDefShotTypeNbr
                        Else
                            'defender is out of ships that can shoot back
                            'Exit For
                            AddListItem "Defender out of shooting ships"
                            intDefenderShipShooting = intDefenderShipShooting - 1
                            Set defendershipshooting = Nothing
                        End If
                    Else
                        DroneBattle2 defendershipshooting, attackerShipShot, blnEndLoop, intDefShootingTypeNbr, intAttShotTypeNbr, intDefenderShipShot, intDefenderShipShooting, intAttackerShipShot, defenderShipShot, intTotalDefenderShip, intDefShotTypeNbr
                    End If
                End If
                
                If Not attackerShipShot.Destroyed Then
                    If (attackerShipShooting.WeaponsLeft = 0) Then
                        'intAttackerShipShooting = intAttackerShipShooting + 1
                        'AddListItem "Attacker: " & attackerShipShooting.Name & " (" & intAttackerShipNbr & ") ship out of weapons"
                        blnEndLoop = True
                    End If
                End If
            Loop
        Next
        
        AddListItem "DEFENDER"
        
        'Loop on the remaining Defender ships that still have weapons left to fire
        For intDefenderShipShooting = 1 To colDefender.Count
            intAttackerShipShot = 1
            
            If Not defendershipshooting Is Nothing Then
                Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, defendershipshooting.Name)
            Else
                Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, "")
            End If
                            
            If Not attackerShipShot Is Nothing Then
                 Set attackerShipShot = GetValidShipAttacker(colAttacker, intAttackerShipShot, False, intAttShotTypeNbr, attackerShipShot.Name)
            Else
                 Set attackerShipShot = GetValidShipAttacker(colAttacker, intAttackerShipShot, False, intAttShotTypeNbr, "")
            End If
            
            blnEndLoop = False
            
            If (intAttackerShipShot > colAttacker.Count) Then
                AddListItem "Attacker out of ships"
                Exit For
            End If
            
            If (intDefenderShipShooting <= intTotalDefenderShip) Then
                'nothing
                'Exit For
            Else
                'defender is out of ships that can shoot back
                'AddListItem "Defender out of shooting ships"
                Exit For
            End If

            'Defender ship shoots at remaining attacking ships until defender is out of weapons
            Do Until blnEndLoop = True
                If (defendershipshooting.WeaponsLeft > 0) Then
                    DroneBattle2 defendershipshooting, attackerShipShot, blnEndLoop, intDefShootingTypeNbr, intAttShotTypeNbr, intDefShootingTypeNbr, intDefenderShipShooting, intAttackerShipShot, defenderShipShot, intTotalDefenderShip, intDefShotTypeNbr
                Else
                    'Defender doesn't shoot back
                    'AddListItem "Defender: " & defenderShipShooting.Name & " (" & intDefenderShipShooting & ") ship out of weapons"
                    
                    'Get next defender ship that can fire a weapon
                    intDefenderShipShooting = intDefenderShipShooting + 1
                    'intDefenderShipNbr = intDefenderShipNbr + 1
                    
                    If (intDefenderShipShooting <= intTotalDefenderShip) Then
                        If Not defendershipshooting Is Nothing Then
                            Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, defendershipshooting.Name)
                        Else
                            Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, "")
                        End If
                    Else
                        'defender is out of ships that can shoot back
                        'Exit For
                        'AddListItem "Defender out of shooting ships"
                        blnEndLoop = True
                        Set defendershipshooting = Nothing
                    End If
                End If
            Loop
        Next
    Next
    
    'Last volley
    AddListItem " "
    AddListItem "Attacker Shots: " & colAttacker.GetHits & " (Total shots = " & (colAttacker.GetHits + colAttacker.GetMisses) & " Misses = " & colAttacker.GetMisses & ")"
    AddListItem "Defender Shots: " & colDefender.GetHits & " (Total shots = " & (colDefender.GetHits + colDefender.GetMisses) & " Misses = " & colDefender.GetMisses & ")"
            
    'Battle is done calculate and post results
    CalculateResult colAttacker, colDefender
    
    Set colAttacker = Nothing
    Set colDefender = Nothing
    Set attackerShipShot = Nothing
    Set defenderShipShot = Nothing
    Set attackerShipShooting = Nothing
    Set defendershipshooting = Nothing
End Function

Private Function CalculateResult(colAttacker As colShipsAttacker, colDefender As colShipsDefender)
    Dim result As clsResult
    Dim colResultsAttacker As Collection
    Dim attackerShip As clsShip
    Dim defenderShip As clsShip
    Dim intI As Integer
    
    Set colResultsAttacker = New Collection
    
    'Attacker
    For intI = 1 To colAttacker.Count
        Set attackerShip = colAttacker.Item(intI, True)
        
        On Error GoTo ErrorControl
        Set result = colResultsAttacker.Item(attackerShip.Name)
        
ErrorControl:
        If Err = 5 Then 'Index is invalid
            Set result = Nothing
            Resume Next
        End If

        If result Is Nothing Then
            Set result = New clsResult
            result.ShipName = attackerShip.Name
            colResultsAttacker.Add result, attackerShip.Name
            Set result = colResultsAttacker.Item(attackerShip.Name)
        End If
        
        If attackerShip.Durability < 1 Then
            'Ship destroyed
            result.ShipsLost = result.ShipsLost + 1
            result.PrestigeLoss = result.PrestigeLoss + attackerShip.Prestige
            result.CrewLoss = result.CrewLoss + attackerShip.Crew
        Else
            'Ship survived
            result.ShipsLeft = result.ShipsLeft + 1
        End If
    Next intI
     
    'Defender
    Dim colResultsDefender As Collection
    Set colResultsDefender = New Collection
    
    For intI = 1 To colDefender.Count
        Set defenderShip = colDefender.Item(intI, False)
        
        On Error GoTo ErrorControl2
        Set result = colResultsDefender.Item(defenderShip.Name)
        
ErrorControl2:
        If Err = 5 Then 'Index is invalid
            Set result = Nothing
            Resume Next
        End If

        If result Is Nothing Then
            Set result = New clsResult
            result.ShipName = defenderShip.Name
            colResultsDefender.Add result, defenderShip.Name
            Set result = colResultsDefender.Item(defenderShip.Name)
        End If
        
        If defenderShip.Durability < 1 Then
            'Ship destroyed
            result.ShipsLost = result.ShipsLost + 1
            result.PrestigeLoss = result.PrestigeLoss + defenderShip.Prestige
            result.CrewLoss = result.CrewLoss + defenderShip.Crew
        Else
             'Ship survived
            result.ShipsLeft = result.ShipsLeft + 1
        End If
    Next intI
    
    Dim intPrestigeLoss As Double
    Dim intCrewLoss As Double
    
    'Writeout end result
    txtResult.Text = "Defender" & vbCrLf
    
    For intI = 1 To colResultsDefender.Count
        Set result = colResultsDefender.Item(intI)
        txtResult.Text = txtResult.Text + result.ShipName & vbTab & " Left: " & result.ShipsLeft & " Lost: " & result.ShipsLost & " Crew lost: " & result.CrewLoss & vbCrLf
        intPrestigeLoss = intPrestigeLoss + result.PrestigeLoss
        intCrewLoss = intCrewLoss + result.CrewLoss
    Next intI
    
    txtResult.Text = txtResult.Text + "Defender: " & intCrewLoss & " total crew loss" & vbCrLf
    txtResult.Text = txtResult.Text + "Defender: " & intPrestigeLoss & " prestige loss" & vbCrLf
    txtResult.Text = txtResult.Text & vbCrLf & "Attacker" & vbCrLf
    
    'reset
    intPrestigeLoss = 0
    intCrewLoss = 0
    
    For intI = 1 To colResultsAttacker.Count
        Set result = colResultsAttacker.Item(intI)
        txtResult.Text = txtResult.Text + result.ShipName & vbTab & " Left: " & result.ShipsLeft & " Lost: " & result.ShipsLost & " Crew lost: " & result.CrewLoss & vbCrLf
        intPrestigeLoss = intPrestigeLoss + result.PrestigeLoss
        intCrewLoss = intCrewLoss + result.CrewLoss
    Next intI
    
    txtResult.Text = txtResult.Text + "Attacker: " & intCrewLoss & " total crew loss" & vbCrLf
    txtResult.Text = txtResult.Text + "Attacker: " & intPrestigeLoss & " prestige loss" & vbCrLf
    
    'Log the result in the logfile
    Log "", ""
    Log "", txtResult.Text
    
    Set attackerShip = Nothing
    Set defenderShip = Nothing
    Set colResultsAttacker = Nothing
    Set colResultsDefender = Nothing
End Function

Private Function DroneBattle2(defendershipshooting As clsShip, attackerShipShot As clsShip, blnEndLoop As Boolean, intDefShootingTypeNbr As Integer, intAttShotTypeNbr As Integer, intDefenderShipShot As Integer, intDefenderShipShooting As Integer, intAttackerShipShot As Integer, defenderShipShot As clsShip, intTotalDefenderShip As Integer, intDefShotTypeNbr As Integer)
    Dim sngOff As Single
    
    'Defender ship fires at attackership
    sngOff = BattleResult(defendershipshooting, attackerShipShot)
    
    'attackerShipShot.Durability = attackerShipShot.Durability - sngOff
    'Add to our list
    If (sngOff <= 0) Then
        AddListItem "Defender: " & defendershipshooting.Name & " (" & intDefShootingTypeNbr & ") fired at " & attackerShipShot.Name & " (" & intAttShotTypeNbr & ") but missed."
    Else
        If (attackerShipShot.Durability > 0) Then
            AddListItem "Defender: " & defendershipshooting.Name & " (" & intDefShootingTypeNbr & ") fired at " & attackerShipShot.Name & " (" & intAttShotTypeNbr & ") and hit it, doing " & sngOff & " damage, Remaining hull strength is " & attackerShipShot.Durability & "."
        Else
            AddListItem "Defender: " & defendershipshooting.Name & " (" & intDefShootingTypeNbr & ") fired at " & attackerShipShot.Name & " (" & intAttShotTypeNbr & ") and hit it for " & sngOff & " damage, destroying it!"
        End If
    End If
    
    'Reduce defender weapon because he fired a shot
    defendershipshooting.SubstractWeapon
    
    If (defendershipshooting.Name = "Stinger Drone") Then
        'A drone just exploded
        defendershipshooting.Durability = 0
        intDefenderShipShot = intDefenderShipShot + 1
        
        If Not defenderShipShot Is Nothing Then
            Set defenderShipShot = GetValidShipDefender(colDefender, intDefenderShipShot, False, intDefShotTypeNbr, defenderShipShot.Name)
        Else
            Set defenderShipShot = GetValidShipDefender(colDefender, intDefenderShipShot, False, intDefShotTypeNbr, "")
        End If
        
        If Not defendershipshooting Is Nothing Then
            Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, defendershipshooting.Name)
        Else
            Set defendershipshooting = GetValidShipDefender(colDefender, intDefenderShipShooting, True, intDefShootingTypeNbr, "")
        End If
        
        If (intDefenderShipShooting <= intTotalDefenderShip) Then
           ' Exit For
        End If
    End If
    
    If attackerShipShot.Destroyed Then
        'Ship is destroyed take next ship
        'AddListItem "Attacker: " & attackerShipShot.Name & " (" & intAttShotTypeNbr & ") ship destroyed"
        
        intAttackerShipShot = intAttackerShipShot + 1
        blnEndLoop = True
    End If
    
End Function

Private Function DroneBattle(defendershipshooting As clsShip, attackerShipShot As clsShip, blnEndLoop As Boolean, intDefenderShipNbr As Integer, intAttackerShipNbr As Integer, intDefenderShipShot As Integer, intDefenderShipShooting As Integer, intAttackerShipShot As Integer, defenderShipShot As clsShip, intTotalDefenderShip As Integer)
    Dim sngOff As Single
    
    'Defender ship fires at attackership
    sngOff = BattleResult(defendershipshooting, attackerShipShot)
    
    'attackerShipShot.Durability = attackerShipShot.Durability - sngOff
    'Add to our list
    If (sngOff <= 0) Then
        colDefender.AddMiss
        AddListItem "Defender: " & defendershipshooting.Name & " (" & intDefenderShipNbr & ") fired at " & attackerShipShot.Name & " (" & intAttackerShipNbr & ") but missed."
    Else
        colDefender.AddHit
        If (attackerShipShot.Durability > 0) Then
            AddListItem "Defender: " & defendershipshooting.Name & " (" & intDefenderShipNbr & ") fired at " & attackerShipShot.Name & " (" & intAttackerShipNbr & ") and hit it, doing " & sngOff & " damage, Remaining hull strength is " & attackerShipShot.Durability & "."
            AddListItem "The drone self-destructed as part of it's attack run"
        Else
            AddListItem "Defender: " & defendershipshooting.Name & " (" & intDefenderShipNbr & ") fired at " & attackerShipShot.Name & " (" & intAttackerShipNbr & ") and hit it for " & sngOff & " damage, destroying it!"
            AddListItem "The drone self-destructed as part of it's attack run"
        End If
    End If
    
    'Reduce defender weapon because he fired a shot
    defendershipshooting.SubstractWeapon
    
    If (defendershipshooting.Name = "Stinger Drone") Then
        'A drone just exploded
        defendershipshooting.Durability = 0
        intDefenderShipShot = intDefenderShipShot + 1
        
        Set defenderShipShot = GetValidShip(colDefender, intDefenderShipShot, False)
        Set defendershipshooting = GetValidShip(colDefender, intDefenderShipShooting, True)
        
        If (intDefenderShipShooting <= intTotalDefenderShip) Then
           ' Exit For
        End If
    End If
    
    If attackerShipShot.Destroyed Then
        'Ship is destroyed take next ship
        'AddListItem "Attacker: " & attackerShipShot.Name & " (" & intAttackerShipNbr & ") ship destroyed"
        
        intAttackerShipShot = intAttackerShipShot + 1
        intAttackerShipNbr = intAttackerShipNbr + 1
        blnEndLoop = True
    End If
    
End Function

Private Sub ComposeFleet()
    'Create a new collection
    Set colAttacker = New colShipsAttacker
    Set colDefender = New colShipsDefender
    
    'First we fill the colAttacker collection with attacking ships
    ComposeAttacker
    
    'sort the collection
    If optNormal.Value = True Then
        'Normal Attack
        'Sort by Offensive capabilties and put figthers at back
        Set colAttacker = SortItemCollectionAttacker(colAttacker, "OffCap", True, True)
        
        AddAttackerFighters False
    ElseIf optFighterScreen.Value = True Then
        'Fighter Screen
        'Sort by Offensive capabilties and put fighters in front

        Set colAttacker = SortItemCollectionAttacker(colAttacker, "SizeRank", True, False)
        
        AddAttackerFighters True
    ElseIf optFleetRecon.Value = True Then
        'Fleet recon
        'Sort by sensor capabilties and put fighters in front

        Set colAttacker = SortItemCollectionAttacker(colAttacker, "Sensor", True, True)
        AddAttackerFighters False
    ElseIf optSensorBlind.Value = True Then
        'Sort by sensor capabilties figthers are also ordered by sensor rating
        AddAttackerFighters False
        'first order by size then sensor rating
        Set colAttacker = SortItemCollectionAttacker(colAttacker, "SizeRank", True, True)
        Set colAttacker = SortItemCollectionAttacker(colAttacker, "Sensor", True, True)
    Else
        'Normal Attack
        'Sort by Offensive capabilties and put figthers at back
        Set colAttacker = SortItemCollectionAttacker(colAttacker, "OffCap", True, True)
        AddAttackerFighters False
    End If
    
    'to see results debugging
    FillListbox
    
    
    'first we fill the colDefender collection with defending ships
    If Not optStructureRecon.Value = True Then
        ComposeDefender
    Else
        'We add no ships during a structure recon attack
    End If
    
    'Defending fleet add fighters and sort fleet
    If optSensorBlind.Value = True Then
        'Defender is attacked with a Sensor blind so defense is ordered by sensor rating
        AddDefenderFighters True
        'first order by size then sensor rating
        Set colDefender = SortItemCollectionDefender(colDefender, "SizeRank", True, True)
        Set colDefender = SortItemCollectionDefender(colDefender, "Sensor", True, True)
    ElseIf optStandard.Value = True Then
        'put ships with high durability up front and put figthers at back
        Set colDefender = SortItemCollectionDefender(colDefender, "Durability", True, True)
        AddDefenderFighters False
    ElseIf optFighterSpread.Value = True Then
        'Fighter Spread
        'put ships with high durability up front and put figthers at front
        'Set colDefender = SortItemCollection(colDefender, "Durability", True, True)

        Set colDefender = SortItemCollectionDefender(colDefender, "SizeRank", True, False)
        AddDefenderFighters True
    ElseIf optStructureRecon.Value = True Then
        'No ships only structures
    Else
        'Ambush
        'Sort by sensors and then durability?
        AddDefenderFighters False
        Set colDefender = SortItemCollectionDefender(colDefender, "Sensor", True, True)
    End If
        
        'Afterwards add defending structures
        
    If optSensorBlind.Value = True Then
        AddDefenderDronesMines
    End If
    
    If Not optFleetRecon.Value = True Then
        'Don't add defending structures when doing a fleet recon
        If optSensorBlind.Value = True Then
            AddStructures True
            AddDefendingStructures True
            AddSurfaceStructures False
        ElseIf optBombard.Value = True Then
            'Bombard
            'all structures are in front they fire first and are targetted first
            AddBombardStructures True
        Else
            AddStructures False
            AddDefendingStructures True
            AddSurfaceStructures True
            
        End If
    End If

    'Drones always sit in front except in a sensor blind
    If Not optSensorBlind.Value = True Then
        AddDefenderDronesMines
    End If
  
    'To see the result
    FillListbox
End Sub

Private Function AddBombardStructures(Optional blnFront As Boolean = True)
    'Structure order in a bombard all structures are in front espicially all shields
    Dim defenderShip As clsShip
    Dim intI As Integer
    
    For intI = 1 To txtJumpgate.Text
        Set defenderShip = New clsJumpgate
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtImpJumpgate.Text
        Set defenderShip = New clsImpJumpgate

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtFolder.Text
        Set defenderShip = New clsFolder

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtOCY.Text
        Set defenderShip = New clsOCY

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPOCY.Text
        Set defenderShip = New clsImpOCY
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtSat.Text
        Set defenderShip = New clsSat
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtImpSat.Text
        Set defenderShip = New clsImpSat
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtTurret.Text
        Set defenderShip = New clsTurret

        AddToDefCollection defenderShip, blnFront
    Next intI

    For intI = 1 To txtSDB.Text
        Set defenderShip = New clsSDB

        AddToDefCollection defenderShip, blnFront
    Next intI

    For intI = 1 To txtIMPSDB.Text
        Set defenderShip = New clsImpSDB

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtODPDEF.Text
        Set defenderShip = New clsODP

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPODPDEF.Text
        Set defenderShip = New clsImpODP

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtODMDEF.Text
        Set defenderShip = New clsODM

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtMonolith.Text
        Set defenderShip = New clsMonolith

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtSSG.Text
        Set defenderShip = New clsSSG

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPSSG.Text
        Set defenderShip = New clsImpSSG
        
        AddToDefCollection defenderShip, blnFront
    Next intI
    
    'For intI = 1 To txtMonolith.Text
    '    Set defenderShip = New clsMonolith
'
'       AddToDefCollection defenderShip, blnFront
'    Next intI
  
    For intI = 1 To txtStarbaseDEF.Text
        Set defenderShip = New clsStarbase

        AddToDefCollection defenderShip, blnFront
    Next intI

    For intI = 1 To txtOSDEF.Text
        Set defenderShip = New clsShield

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtIMPOSDEF.Text
        Set defenderShip = New clsImpShield

        AddToDefCollection defenderShip, blnFront
    Next intI
    
    For intI = 1 To txtOBDEF.Text
        Set defenderShip = New clsBulwark

        AddToDefCollection defenderShip, blnFront
    Next intI


    Set defenderShip = Nothing
    
End Function

Private Function AddDefenderDronesMines()
    'Drones and mines are always in front
    Dim intI As Integer
    Dim defenderShip As clsShip
    
    For intI = 1 To txtOMDEF.Text
        Set defenderShip = New clsOM

        If intDefenderDurability > 0 Then
            defenderShip.Durability = defenderShip.Durability + Round(((defenderShip.Durability * intDefenderDurability) / 100), 0)
        End If
        
        If intDefenderOffense > 0 Then
            defenderShip.OffCap = defenderShip.OffCap + Round(((defenderShip.OffCap * intDefenderOffense) / 100), 0)
            defenderShip.OffFight = defenderShip.OffFight + Round(((defenderShip.OffFight * intDefenderOffense) / 100), 0)
            defenderShip.OffStruct = defenderShip.OffStruct + Round(((defenderShip.OffStruct * intDefenderOffense) / 100), 0)
        End If
        
        If intDefenderDefense > 0 Then
            defenderShip.DefCap = defenderShip.DefCap + Round(((defenderShip.DefCap * intDefenderDefense) / 100), 0)
            defenderShip.DefFight = defenderShip.DefFight + Round(((defenderShip.DefFight * intDefenderDefense) / 100), 0)
        End If
        
        If colDefender.Count = 0 Then
             colDefender.Add defenderShip
        Else
             colDefender.AddBefore defenderShip, 1
        End If
    Next intI
    
    For intI = 1 To txtIMPOMDEF.Text
        Set defenderShip = New clsImpOM

        If intDefenderDurability > 0 Then
            defenderShip.Durability = defenderShip.Durability + Round(((defenderShip.Durability * intDefenderDurability) / 100), 0)
        End If
        
        If intDefenderOffense > 0 Then
            defenderShip.OffCap = defenderShip.OffCap + Round(((defenderShip.OffCap * intDefenderOffense) / 100), 0)
            defenderShip.OffFight = defenderShip.OffFight + Round(((defenderShip.OffFight * intDefenderOffense) / 100), 0)
            defenderShip.OffStruct = defenderShip.OffStruct + Round(((defenderShip.OffStruct * intDefenderOffense) / 100), 0)
        End If
        
        If intDefenderDefense > 0 Then
            defenderShip.DefCap = defenderShip.DefCap + Round(((defenderShip.DefCap * intDefenderDefense) / 100), 0)
            defenderShip.DefFight = defenderShip.DefFight + Round(((defenderShip.DefFight * intDefenderDefense) / 100), 0)
        End If
        
        If colDefender.Count = 0 Then
             colDefender.Add defenderShip
        Else
             colDefender.AddBefore defenderShip, 1
        End If
    Next intI
    
    For intI = 1 To txtDrone.Text
        Set defenderShip = New clsStinger

        If intDefenderDurability > 0 Then
            defenderShip.Durability = defenderShip.Durability + Round(((defenderShip.Durability * intDefenderDurability) / 100), 0)
        End If
        
        If intDefenderOffense > 0 Then
            defenderShip.OffCap = defenderShip.OffCap + Round(((defenderShip.OffCap * intDefenderOffense) / 100), 0)
            defenderShip.OffFight = defenderShip.OffFight + Round(((defenderShip.OffFight * intDefenderOffense) / 100), 0)
            defenderShip.OffStruct = defenderShip.OffStruct + Round(((defenderShip.OffStruct * intDefenderOffense) / 100), 0)
        End If
        
        If intDefenderDefense > 0 Then
            defenderShip.DefCap = defenderShip.DefCap + Round(((defenderShip.DefCap * intDefenderDefense) / 100), 0)
            defenderShip.DefFight = defenderShip.DefFight + Round(((defenderShip.DefFight * intDefenderDefense) / 100), 0)
        End If
        
        'Drones are ALWAYS in front
        If colDefender.Count = 0 Then
             colDefender.Add defenderShip
        Else
            colDefender.AddBefore defenderShip, 1
        End If
    Next intI
    
    Set defenderShip = Nothing
End Function

Private Function FillListbox()
    Dim intI As Integer
    Dim Ship As clsShip
    
    lstAttacker.Clear
    lstAttacker.Visible = False
    If Not colAttacker Is Nothing Then
        For intI = 1 To colAttacker.Count
            Set Ship = colAttacker.Item(intI, True)
            lstAttacker.AddItem Ship.Name
        Next intI
    End If
    lstAttacker.Visible = True
    
    lstDefender.Clear
    lstDefender.Visible = False
    If Not colDefender Is Nothing Then
        For intI = 1 To colDefender.Count
            Set Ship = colDefender.Item(intI, False)
            lstDefender.AddItem Ship.Name
        Next intI
    End If
    lstDefender.Visible = True
    
    Set Ship = Nothing
End Function

Private Sub ReadINIFile()
    Dim strString  As String
    Dim lSize    As Long
    Dim lReturn  As Long
    Dim strFile As String

    strFile = App.Path + "\battlesim.ini"
    
    strString = String$(10, "*")
    lSize = Len(strString)

    'Get the formula base values
    lReturn = GetPrivateProfileString("Options", "BattleFormula", "0", strString, lSize, strFile)
    cmbFormula.ListIndex = CInt(strString)
    
    lReturn = GetPrivateProfileString("Options", "RandomFactor", "3", strString, lSize, strFile)
    sngRandomfactor = CSng(strString)
    
    lReturn = GetPrivateProfileString("Options", "RandomDropOffRatio", "6", strString, lSize, strFile)
    sngRandomDropOffRatio = CSng(strString)
    
    lReturn = GetPrivateProfileString("Options", "CriticalHitRatio", "4.5", strString, lSize, strFile)
    sngCriticalHitRatio = CSng(strString)
End Sub

Private Sub cmdFight_Click()
    MousePointer = vbHourglass
    txtResult.Text = ""
    
    ReadINIFile
    
    strLogFile = "Battle " & Replace(FormatDateTime(Now, vbGeneralDate), ":", "") & ".txt"

    'Compose fleet for testing purposes
    intAttackerDefense = txtAttackerDefense.Text
    intAttackerDurability = txtAttackerDurability.Text
    intAttackerOffense = txtAttackerOffense.Text
    
    intDefenderDefense = txtDefenderDefense.Text
    intDefenderDurability = txtDefenderDurability.Text
    intDefenderOffense = txtDefenderOffense.Text
    
    'Determine Defender Bonusses
    If optStandard.Value = True Then
       'No bonusses
    ElseIf optFighterSpread.Value = True Then
        'Fighter Spread
        'Grant a 5% off and 5% defense bonus
        intDefenderDefense = intDefenderDefense + 5
        intDefenderOffense = intDefenderOffense + 5
    Else
        'Ambush
        '10% offense bonus
        intDefenderOffense = intDefenderOffense + 10
    End If
    
    'Attacker bonusses
    If optNormal.Value = True Then
        'No bonusses
    ElseIf optFleetRecon.Value = True Then
        'Fleetrecon
        intAttackerOffense = intAttackerOffense - 20
        intAttackerDefense = intAttackerDefense - 20
    ElseIf optSensorBlind.Value = True Then
        'No bonus
    ElseIf optBombard.Value = True Then
        'Bombard
        'Grants a 10% offense bonus and a 10% defense penalty to the attacker.
        If chkRandom.Value = 1 Then 'Checked
            'Random bombardment
            'Grants a 15% off bonus and a 20% defense penalty to the attacker
            intAttackerOffense = intAttackerOffense + 15
            intAttackerDefense = intAttackerDefense - 20
        Else
            'Industry or Military bombard
            intAttackerOffense = intAttackerOffense + 10
            intAttackerDefense = intAttackerDefense - 10
        End If
    End If
    
    If (optBombard.Value = True Or optSensorBlind.Value = True) Then
        If CInt(txtVolleys.Text) >= 4 Then
            'Max 3 volleys in bombard or sensor blind
            txtVolleys.Text = 3
        End If
    End If
    
    'Compose the attacker and defending fleet
    ComposeFleet
    
    'Do the fighting
    'BattleEngine
    
    lstResult.Visible = False
    BattleEngine2
    lstResult.Visible = True
    
    MousePointer = vbNormal
End Sub

Public Function Rand(ByVal Low As Single, ByVal High As Single) As Single
    Dim sngRand As Single
    
    sngRand = Rnd
    Rand = CSng((High - Low) * sngRand) + Low
End Function

Private Sub AddListItem(strItemToAdd As String)
    lstResult.AddItem strItemToAdd
    Log "", strItemToAdd
End Sub

Private Sub Log(sFile As String, sText As String)
    On Error Resume Next

    'Open App.Path & "\battle.txt" For Append As #1 'Append As #1
    Open App.Path & "\" & strLogFile For Append As #1 'Append As #1
    
    Print #1, sText
    Close #1
End Sub

'Private Sub cmd_Up_Click()
'    Dim i As Long
'    Dim leaveAlone As Boolean
'    Dim pos As Long
'    Dim Temp As String
'
'    pos = 0
'
'    For i = 0 To ListBox1.ListCount - 1
'       leaveAlone = False
'
'       If ListBox1.Selected(i) Then
'
'         If i = pos Then
'          leaveAlone = True
'         End If
'         pos = pos + 1
'
'         If leaveAlone = False Then
'          Temp = ListBox1.List(i - 1)
'          ListBox1.List(i - 1) = ListBox1.List(i)
'          ListBox1.List(i) = Temp
'          ListBox1.ListIndex = i - 1
'          ListBox1.Selected(i) = False
'          ListBox1.Selected(i - 1) = True
'         End If
'      End If
'    Next
'End Sub

'Private Sub cmd_Down_Click()
'    Dim i As Integer
'    Dim leaveAlone As Boolean
'    Dim pos As Long
'    Dim Temp As String
'
'    pos = ListBox1.ListCount - 1
'
'    For i = ListBox1.ListCount - 1 To 0 Step -1
'
'      leaveAlone = False
'      If ListBox1.Selected(i) Then
'
'         If i = pos Then
'          leaveAlone = True
'         End If
'
'         pos = pos - 1
'
'          If Not leaveAlone Then
'            Temp = ListBox1.List(i + 1)
'            ListBox1.List(i + 1) = ListBox1.List(i)
'            ListBox1.List(i) = Temp
'            ListBox1.ListIndex = i + 1
'            ListBox1.Selected(i) = False
'            ListBox1.Selected(i + 1) = True
'          End If
'       End If
'    Next
'End Sub

Private Sub WriteINIFile()
    'Write back the Formula option
    Dim lReturn  As Long
    Dim strFile As String
    strFile = App.Path + "\battlesim.ini"
   
    'Write settings to inifile
    lReturn = WritePrivateProfileString("Options", "BattleFormula", CStr(cmbFormula.ListIndex), strFile)
    
    lReturn = WritePrivateProfileString("Options", "RandomFactor", CStr(sngRandomfactor), strFile)
    lReturn = WritePrivateProfileString("Options", "RandomDropOffRatio", CStr(sngRandomDropOffRatio), strFile)
    lReturn = WritePrivateProfileString("Options", "CriticalHitRatio", CStr(sngCriticalHitRatio), strFile)
End Sub
Private Sub Form_Unload(Cancel As Integer)
   WriteINIFile
End Sub
