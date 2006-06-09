VERSION 5.00
Begin VB.Form frmTurnCounter 
   Caption         =   "Stellar-Realms Turn Counter"
   ClientHeight    =   1950
   ClientLeft      =   2115
   ClientTop       =   2445
   ClientWidth     =   4470
   Icon            =   "stellar.frx":0000
   LinkTopic       =   "Form1"
   ScaleHeight     =   1950
   ScaleWidth      =   4470
   Begin VB.Frame Frame1 
      Height          =   1695
      Left            =   120
      TabIndex        =   0
      Top             =   120
      Width           =   4215
      Begin VB.TextBox txtTurn 
         Height          =   315
         Left            =   120
         MaxLength       =   6
         TabIndex        =   2
         Text            =   "1"
         Top             =   1200
         Width           =   855
      End
      Begin VB.CommandButton cmdCalculate 
         Caption         =   "Calculate"
         Default         =   -1  'True
         Height          =   375
         Left            =   2880
         TabIndex        =   1
         Top             =   1200
         Width           =   1215
      End
      Begin VB.Label lbl1 
         Caption         =   "Current date/time:"
         Height          =   255
         Left            =   120
         TabIndex        =   7
         Top             =   240
         Width           =   1455
      End
      Begin VB.Label lblCurrentTime 
         Height          =   255
         Left            =   2040
         TabIndex        =   6
         Top             =   240
         Width           =   1815
      End
      Begin VB.Label lbl2 
         Caption         =   "turns"
         Height          =   255
         Left            =   1080
         TabIndex        =   5
         Top             =   1200
         Width           =   495
      End
      Begin VB.Label lbl3 
         Caption         =   "Completition date/time:"
         Height          =   255
         Left            =   120
         TabIndex        =   4
         Top             =   720
         Width           =   1695
      End
      Begin VB.Label lblCompletionTime 
         Height          =   255
         Left            =   2040
         TabIndex        =   3
         Top             =   720
         Width           =   1935
      End
   End
End
Attribute VB_Name = "frmTurnCounter"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub cmdCalculate_Click()
    'Calculate endtime based on the number of turns
    '1 turn takes 20 minutes
    Dim intTurnMinutes As Integer
    Dim lngTotalMinutes As Long
    Dim lngTotalTurns As Long
    Dim tmpTime As Date
    Dim intMinutesDifference As Integer
    
    If IsNumeric(txtTurn.Text) Then
        lngTotalTurns = CLng(txtTurn.Text) 'turns we have to add

        If lngTotalTurns > 0 Then
            intTurnMinutes = 20 '20 minutes
            tmpTime = Now 'get the current time
            
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
            lngTotalMinutes = lngTotalTurns * intTurnMinutes
            lblCompletionTime = Format(DateAdd("n", lngTotalMinutes, tmpTime), "dd-mmm-yyyy hh:mm")
        Else
            lblCompletionTime = ""
        End If
        
        lblCurrentTime.Caption = Format(Now, "dd-mmm-yyyy hh:mm")
    Else
        MsgBox "Please enter numeric data only", vbExclamation, App.Title
    End If
    
End Sub

Private Sub Form_Load()
    lblCurrentTime.Caption = Format(Now, "dd-mmm-yyyy hh:mm")
End Sub
