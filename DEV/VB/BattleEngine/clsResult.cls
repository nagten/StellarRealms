VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "clsResult"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Private m_intShipsLost As Double
Private m_intShipsLeft As Double
Private m_intCrewLoss As Double
Private m_strShipName As String
Private m_intPrestigeLoss As Double
Private m_sngFuelFactor As Single
Private m_intSpeed As Integer

Public Property Get PrestigeLoss() As Double
    PrestigeLoss = m_intPrestigeLoss
End Property

Public Property Let PrestigeLoss(ByVal intPrestigeLoss As Double)
    m_intPrestigeLoss = intPrestigeLoss
End Property

Public Property Get ShipsLost() As Double
    ShipsLost = m_intShipsLost
End Property

Public Property Let ShipsLost(ByVal intShipsLost As Double)
    m_intShipsLost = intShipsLost
End Property

Public Property Get ShipsLeft() As Double
    ShipsLeft = m_intShipsLeft
End Property

Public Property Let ShipsLeft(ByVal intShipsLeft As Double)
    m_intShipsLeft = intShipsLeft
End Property

Public Property Get CrewLoss() As Double
    CrewLoss = m_intCrewLoss
End Property

Public Property Let CrewLoss(ByVal intCrewLoss As Double)
    m_intCrewLoss = intCrewLoss
End Property

Public Property Get ShipName() As String
    ShipName = m_strShipName
End Property

Public Property Let ShipName(ByVal strShipName As String)
    m_strShipName = strShipName
End Property

Public Property Get FuelFactor() As Single
    FuelFactor = m_sngFuelFactor
End Property

Public Property Let FuelFactor(ByVal sngFuelFactor As Single)
    m_sngFuelFactor = sngFuelFactor
End Property

Public Property Get Speed() As Integer
    Speed = m_intSpeed
End Property

Public Property Let Speed(ByVal intSpeed As Integer)
    m_intSpeed = intSpeed
End Property
