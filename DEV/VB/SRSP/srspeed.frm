VERSION 5.00
Begin VB.Form frmsrspeed 
   Caption         =   "SR Speed Calculator"
   ClientHeight    =   2430
   ClientLeft      =   7800
   ClientTop       =   9555
   ClientWidth     =   5880
   Icon            =   "srspeed.frx":0000
   LinkTopic       =   "Form1"
   ScaleHeight     =   2430
   ScaleWidth      =   5880
   StartUpPosition =   1  'CenterOwner
   Begin VB.TextBox txtDeploy 
      Alignment       =   2  'Center
      Height          =   285
      Left            =   3840
      MaxLength       =   3
      TabIndex        =   16
      Text            =   "0"
      Top             =   1320
      Width           =   495
   End
   Begin VB.Frame frmtargetspeed 
      Caption         =   "Turns"
      Height          =   1095
      Left            =   2160
      TabIndex        =   10
      Top             =   120
      Width           =   3615
      Begin VB.Label lblResultBack 
         Alignment       =   2  'Center
         Height          =   255
         Left            =   1440
         TabIndex        =   14
         Top             =   720
         Width           =   2055
      End
      Begin VB.Label Label2 
         Caption         =   "Back from target: "
         Height          =   255
         Left            =   120
         TabIndex        =   13
         Top             =   720
         Width           =   1215
      End
      Begin VB.Label lblResult 
         Alignment       =   2  'Center
         Height          =   255
         Left            =   1440
         TabIndex        =   12
         Top             =   360
         Width           =   2055
      End
      Begin VB.Label Label1 
         Caption         =   "To target: "
         Height          =   255
         Left            =   120
         TabIndex        =   11
         Top             =   360
         Width           =   735
      End
   End
   Begin VB.Frame frmJumpStructure 
      Caption         =   "Jump structure"
      Height          =   1695
      Left            =   120
      TabIndex        =   5
      Top             =   120
      Width           =   1815
      Begin VB.OptionButton optNoGate 
         Caption         =   "none"
         Height          =   255
         Left            =   120
         TabIndex        =   9
         Top             =   240
         Value           =   -1  'True
         Width           =   975
      End
      Begin VB.OptionButton optJumpGate 
         Caption         =   "Jumpgate"
         Height          =   255
         Left            =   120
         TabIndex        =   8
         Top             =   600
         Width           =   1215
      End
      Begin VB.OptionButton optImpJumpGate 
         Caption         =   "Imp Jumpgate"
         Height          =   255
         Left            =   120
         TabIndex        =   7
         Top             =   960
         Width           =   1335
      End
      Begin VB.OptionButton optFolder 
         Caption         =   "Space Folder"
         Height          =   255
         Left            =   120
         TabIndex        =   6
         Top             =   1320
         Width           =   1455
      End
   End
   Begin VB.TextBox txtShipSpeed 
      Alignment       =   2  'Center
      Height          =   285
      Left            =   3840
      MaxLength       =   2
      TabIndex        =   2
      Text            =   "2"
      Top             =   2040
      Width           =   495
   End
   Begin VB.TextBox txtResearchBonus 
      Alignment       =   2  'Center
      Height          =   285
      Left            =   3840
      MaxLength       =   3
      TabIndex        =   1
      Text            =   "-6"
      Top             =   1680
      Width           =   495
   End
   Begin VB.CommandButton cmdCalculate 
      Caption         =   "Calculate"
      Default         =   -1  'True
      Height          =   375
      Left            =   4560
      TabIndex        =   0
      Top             =   1920
      Width           =   1215
   End
   Begin VB.Label lblDeploy 
      Caption         =   " Deploying in:"
      Height          =   255
      Left            =   2640
      TabIndex        =   15
      Top             =   1320
      Width           =   1215
   End
   Begin VB.Label lblShipSpeed 
      Caption         =   "Speed of slowest ship:"
      Height          =   255
      Left            =   2040
      TabIndex        =   4
      Top             =   2040
      Width           =   1695
   End
   Begin VB.Label lblresearchbonus 
      Caption         =   "Research:"
      Height          =   255
      Left            =   2880
      TabIndex        =   3
      Top             =   1680
      Width           =   855
   End
End
Attribute VB_Name = "frmsrspeed"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
                  
Private Sub cmdCalculate_Click()
    'First we check if we got a jumpgate
    Dim intJumpspeed As Integer
   
    If optNoGate.Value = True Then
        intJumpspeed = 0
    ElseIf optJumpGate.Value = True Then
        intJumpspeed = 4
    ElseIf optImpJumpGate.Value = True Then
        intJumpspeed = 6
    ElseIf optFolder.Value = True Then
        intJumpspeed = 8
    End If
    
    'Check userinput
    If IsNumeric(txtResearchBonus.Text) And IsNumeric(txtShipSpeed.Text) And IsNumeric(txtDeploy.Text) Then
        Dim intSpeedbonus As Integer
        Dim intSpeedToTarget As Integer
        Dim intSpeedFromTarget As Integer
        Dim lngDeployTurns As Long
        Dim currenttime As Date
    
        lngDeployTurns = CLng(txtDeploy.Text)
        currenttime = Format(Now, "dd-mmm-yyyy hh:mm")
        intSpeedbonus = Round((-0.2) * txtResearchBonus.Text, 0)
        
        'calculate speed to target
        intSpeedToTarget = 20 + intSpeedbonus - txtShipSpeed - intJumpspeed
        lblResult.Caption = intSpeedToTarget & "  (" & Format(CalculateTime(currenttime, (intSpeedToTarget + lngDeployTurns)), "dd-mmm-yyyy hh:mm") & ")"
        
        'calculate speed back from target
        intSpeedFromTarget = 20 + intSpeedbonus - txtShipSpeed
        lblResultBack.Caption = intSpeedFromTarget & "  (" & Format(CalculateTime(currenttime, ((intSpeedToTarget + intSpeedFromTarget) + lngDeployTurns)), "dd-mmm-yyyy hh:mm") & ")"
    Else
        MsgBox "Use numerical values", vbOKOnly, "Error"
    End If
End Sub

Private Function CalculateTime(tmpTime As Date, intSpeed As Integer) As Date
    Dim intTurnMinutes As Integer
    Dim intMinutesDifference As Integer
    Dim lngTotalMinutes As Long
    
    intTurnMinutes = 20 '20 minutes
    intMinutesDifference = CInt(DatePart("n", tmpTime)) 'get the minutes difference

    Select Case intMinutesDifference
    Case Is < 20
        'first 20 minutes
        tmpTime = DateAdd("n", -(intMinutesDifference), tmpTime)
    Case Is < 40
        'below 40 minutes
        tmpTime = DateAdd("n", -(intMinutesDifference - 20), tmpTime)
    Case Else
        'above 40 minutes
        tmpTime = DateAdd("n", -(intMinutesDifference - 40), tmpTime)
    End Select
    
    'Now we have the correct time
    lngTotalMinutes = intSpeed * intTurnMinutes
    CalculateTime = Format(DateAdd("n", lngTotalMinutes, tmpTime), "dd-mmm-yyyy hh:mm")
End Function

Private Sub Form_Load()
    Dim strString  As String
    Dim lSize    As Long
    Dim lReturn  As Long
    Dim strFile As String
    strFile = App.Path + "\srspeed.ini"
    
    strString = String$(10, "*")
    lSize = Len(strString)
    
    lReturn = GetPrivateProfileString("Options", "ResearchBonus", "0", strString, lSize, strFile)
    txtResearchBonus.Text = strString
    
    lReturn = GetPrivateProfileString("Options", "ShipSpeed", "2", strString, lSize, strFile)
    txtShipSpeed.Text = strString
End Sub

Private Sub Form_Unload(Cancel As Integer)
    Dim lReturn  As Long
    Dim strFile As String
    strFile = App.Path + "\srspeed.ini"
    'Write settings to inifile, research bonus and speed of slowest ship
    lReturn = WritePrivateProfileString("Options", "ResearchBonus", txtResearchBonus.Text, strFile)
    
    lReturn = WritePrivateProfileString("Options", "ShipSpeed", txtShipSpeed.Text, strFile)
End Sub
