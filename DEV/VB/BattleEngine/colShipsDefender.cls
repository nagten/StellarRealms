VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "colShipsDefender"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Private colShips As New Collection
Private intShipType As Integer
Private intPreviousIndex As Integer
Private intAttackShipType As Integer
Private intAttackPreviousIndex As Integer
Private strPreviousShipName As String
Private strAttackPreviousShipName As String
Private intDefenderShotsHit As Integer
Private intDefenderShotsMiss As Integer

Private Sub Class_Initialize()
    intShipType = 0
    intPreviousIndex = 0
    strPreviousShipName = ""
End Sub

Public Function GetHits()
    GetHits = intDefenderShotsHit
End Function

Public Function GetMisses()
    GetMisses = intDefenderShotsMiss
End Function

Public Function AddHit()
    intDefenderShotsHit = intDefenderShotsHit + 1
End Function

Public Function AddMiss()
    intDefenderShotsMiss = intDefenderShotsMiss + 1
End Function

Public Function ResetShots()
    intDefenderShotsHit = 0
    intDefenderShotsMiss = 0
End Function

Public Function ResetCounters()
    intShipType = 0
    intPreviousIndex = 0
    strPreviousShipName = ""
    
    intAttackShipType = 0
    intAttackPreviousIndex = 0
    strAttackPreviousShipName = ""
End Function

'Public Function Item(ByVal Index As String) As clsShip
'    'Use error checker in case index doesn't refer to an object
'    On Error GoTo ErrorControl
'
'    Set Item = colShips.Item(Index)
'
'    Exit Function
'
'ErrorControl:
'    If Err = 5 Then 'Index is invalid
'        Set Item = Nothing
'        Resume Next
'    End If
'End Function

Public Function Item(ByVal intIndex As Integer, blnAttack As Boolean) As clsShip
    'Use error checker in case index doesn't refer to an object
    On Error GoTo ErrorControl
    
    Set Item = colShips(intIndex)
    
    If blnAttack Then
        If (strAttackPreviousShipName <> Item.Name) Then
            intAttackShipType = 1
            intAttackPreviousIndex = intIndex
            strAttackPreviousShipName = Item.Name
        Else
            If (intAttackPreviousIndex <> intIndex) Then
                intAttackShipType = intAttackShipType + 1
                intAttackPreviousIndex = intIndex
            End If
        End If
    Else
        If (strPreviousShipName <> Item.Name) Then
            intShipType = 1
            intPreviousIndex = intIndex
            strPreviousShipName = Item.Name
        Else
            If (intPreviousIndex <> intIndex) Then
                intShipType = intShipType + 1
                intPreviousIndex = intIndex
            End If
        End If
    End If
    
    Exit Function
    
ErrorControl:
    If Err = 5 Then 'Index is invalid
        Set Item = Nothing
        Resume Next
    End If
End Function

Public Function Count() As Integer
    Count = colShips.Count
End Function

Public Function ShipTypeNbr(blnAttack As Boolean) As Integer
    If blnAttack Then
        ShipTypeNbr = intAttackShipType
    Else
        ShipTypeNbr = intShipType
    End If
End Function

Public Sub Add(ShipObject As clsShip)
    'This sub adds a reference to the passed in ShipObject to this collection
    colShips.Add ShipObject
End Sub

Public Sub AddKey(ShipObject As clsShip, strKey As String)
    'This sub adds a reference to the passed in ShipObject to this collection
    colShips.Add ShipObject, strKey
End Sub

Public Sub AddBefore(ShipObject As clsShip, before As Integer)
    'This sub adds a reference to the passed in ShipObject to this collection
    colShips.Add ShipObject, , before
End Sub

Public Sub AddAfter(ShipObject As clsShip, after As Integer)
    'This sub adds a reference to the passed in ShipObject to this collection
    colShips.Add ShipObject, , , after
End Sub
