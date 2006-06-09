Attribute VB_Name = "Module1"
Declare Function SetTimer Lib "user32" (ByVal hwnd As Long, ByVal nIDEvent As Long, ByVal uElapse As Long, ByVal lpTimerfunc As Long) As Long
Declare Function KillTimer Lib "user32" (ByVal hwnd As Long, ByVal nIDEvent As Long) As Long

Private TimerID As Long
Public TimerActive As Boolean

Public Sub ActivateTimer(ByVal minute As Long)
  minute = minute * 1000 * 60
  If TimerActive Then Call DeactivateTimer
  TimerID = SetTimer(0, 0, minute, AddressOf Timer_CBK)
End Sub
Public Sub DeactivateTimer()
  KillTimer 0, TimerID
End Sub
Sub Timer_CBK(ByVal hwnd As Long, ByVal uMsg As Long, ByVal idevent As Long, ByVal Systime As Long)
  Beep
  ' place code here for events upon timer mark such as a message box
  If TimerActive Then Call DeactivateTimer
End Sub
