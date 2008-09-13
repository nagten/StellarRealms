VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "clsMaelstrom"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Implements clsShip

Private m_intShips As Integer
Private m_intShipsLeft As Integer
Private m_intWeapons As Integer
Private m_intWeaponsLeft As Integer
Private m_sngDurability As Single
Private m_sngBaseDurability As Single
Private m_intOffCap As Integer
Private m_intOffFight As Integer
Private m_intDefCap As Integer
Private m_intDefFight As Integer
Private m_intShipType As Integer
Private m_intOffStruct As Integer
Private m_intSensor As Integer
Private m_intCrew As Integer
Private m_strName As String
Private m_intPrestige As Integer
Private m_intSizeRank As Long
Private m_lngCost As Long
Private m_sngFuelFactor As Single
Private m_intSpeed as Integer


Private Sub Class_Initialize()
    m_strName = "Maelstrom Siege Platform"
    m_intWeapons = 5
    m_intWeaponsLeft = 5
    m_sngDurability = 140
    m_sngBaseDurability = 140
    m_intOffCap = 16
    m_intOffFight = 2
    m_intOffStruct = 24
    m_intDefCap = 10
    m_intDefFight = 10
    m_intShipType = 0 '0 = Capital 1 = Fighter 2 = structure
    m_intSensor = 0
    m_intPrestige = 1305
    m_intCrew = 2000
    m_intSizeRank = 46
    m_lngCost = 17200
    m_intSpeed = 1
    m_sngFuelFactor = ReadShipValues("Maelstrom", "FuelFactor")
End Sub

Private Property Let clsShip_SizeRank(ByVal RHS As Integer)
    m_intSizeRank = RHS
End Property

Private Property Get clsShip_SizeRank() As Integer
    clsShip_SizeRank = m_intSizeRank
End Property

Private Property Let clsShip_Cost(ByVal RHS As Long)
    m_lngCost = RHS
End Property

Private Property Get clsShip_Cost() As Long
    clsShip_Cost = m_lngCost
End Property

Private Property Let clsShip_Prestige(ByVal RHS As Integer)
    m_intPrestige = RHS
End Property

Private Property Get clsShip_Prestige() As Integer
    clsShip_Prestige = m_intPrestige
End Property

Private Function clsShip_ResetDurability() As Variant
    m_sngDurability = m_sngBaseDurability
End Function

Public Property Get clsShip_Crew() As Integer
    clsShip_Crew = m_intCrew
End Property

Public Property Get clsShip_DefCap() As Integer
    clsShip_DefCap = m_intDefCap
End Property

Public Property Let clsShip_DefCap(ByVal RHS As Integer)
    m_intDefCap = RHS
End Property

Public Property Get clsShip_DefFight() As Integer
    clsShip_DefFight = m_intDefFight
End Property

Public Property Let clsShip_DefFight(ByVal RHS As Integer)
    m_intDefFight = RHS
End Property

Private Function clsShip_Destroyed() As Boolean
    If m_sngDurability < 1 Then
        clsShip_Destroyed = True
    Else
        clsShip_Destroyed = False
    End If
End Function

Public Property Get clsShip_Durability() As Single
    clsShip_Durability = m_sngDurability
End Property

Public Property Let clsShip_Durability(ByVal RHS As Single)
    m_sngDurability = RHS
End Property

Public Property Get clsShip_Name() As String
    clsShip_Name = m_strName
End Property

Public Property Get clsShip_OffCap() As Integer
    clsShip_OffCap = m_intOffCap
End Property

Public Property Let clsShip_OffCap(ByVal RHS As Integer)
    m_intOffCap = RHS
End Property

Public Property Get clsShip_OffFight() As Integer
    clsShip_OffFight = m_intOffFight
End Property

Public Property Let clsShip_OffFight(ByVal RHS As Integer)
    m_intOffFight = RHS
End Property

Public Property Let clsShip_OffStruct(ByVal RHS As Integer)
    m_intOffStruct = RHS
End Property

Public Property Get clsShip_OffStruct() As Integer
    clsShip_OffStruct = m_intOffStruct
End Property

Private Function clsShip_ResetWeapons()
    m_intWeaponsLeft = m_intWeapons
End Function

Public Property Get clsShip_Sensor() As Integer
    clsShip_Sensor = m_intSensor
End Property

Private Property Let clsShip_Ships(ByVal RHS As Integer)
    m_intShips = RHS
End Property

Private Property Get clsShip_Ships() As Integer
    clsShip_Ships = m_intShips
End Property

Private Property Get clsShip_ShipsLeft() As Integer
    clsShip_ShipsLeft = m_intShipsLeft
End Property

Public Property Get clsShip_ShipType() As Integer
    clsShip_ShipType = m_intShipType
End Property

Private Function clsShip_SubstractShip()
    m_intShipsLeft = m_intShipsLeft - 1
End Function

Private Function clsShip_SubstractWeapon()
    m_intWeaponsLeft = m_intWeaponsLeft - 1
End Function

Public Property Get clsShip_Weapons() As Integer
    clsShip_Weapons = m_intWeapons
End Property

Private Property Let clsShip_WeaponsLeft(ByVal RHS As Integer)
    m_intWeaponsLeft = RHS
End Property

Private Property Get clsShip_WeaponsLeft() As Integer
    clsShip_WeaponsLeft = m_intWeaponsLeft
End Property

Private Property Let clsShip_ShipsLeft(ByVal RHS As Integer)
    m_intShipsLeft = RHS
End Property

Private Property Get clsShip_FuelFactor() As Single
    clsShip_FuelFactor = m_sngFuelFactor
End Property

Private Property Let clsShip_FuelFactor(ByVal RHS As Single)
    m_sngFuelFactor = RHS
End Property

Private Property Get clsShip_Speed() As Integer
    clsShip_Speed = m_intSpeed
End Property

Private Property Let clsShip_Speed(ByVal RHS As Integer)
    m_intSpeed = RHS
End Property