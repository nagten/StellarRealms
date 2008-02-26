VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "clsShip"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Public Function ResetDurability()
End Function

Public Function Destroyed() As Boolean
End Function

Public Function ResetWeapons()
End Function

Public Function SubstractShip()
End Function

Public Function SubstractWeapon()
End Function

Public Property Get SizeRank() As Integer
End Property

Public Property Let SizeRank(ByVal intSizeRank As Integer)
End Property

Public Property Get Cost() As Long
End Property

Public Property Let Cost(ByVal intCost As Long)
End Property

Public Property Get ShipsLeft() As Integer
End Property

Public Property Let ShipsLeft(ByVal intShips As Integer)
End Property

Public Property Get Ships() As Integer
End Property

Public Property Let Ships(ByVal intShips As Integer)
End Property

Public Property Get WeaponsLeft() As Integer
End Property

Public Property Let WeaponsLeft(ByVal intWeaponsLeft As Integer)
End Property

Public Property Get Weapons() As Integer
End Property

Public Property Get Durability() As Single
End Property

Public Property Let Durability(ByVal intDurability As Single)
End Property

Public Property Get OffCap() As Integer
End Property

Public Property Let OffCap(ByVal intOffCap As Integer)
End Property

Public Property Get OffFight() As Integer
End Property

Public Property Let OffFight(ByVal intOffFight As Integer)
End Property

Public Property Get DefCap() As Integer
End Property

Public Property Let DefCap(ByVal intDefCap As Integer)
End Property

Public Property Get DefFight() As Integer
End Property

Public Property Let DefFight(ByVal intDefFight As Integer)
End Property

Public Property Get ShipType() As Integer
End Property

Public Property Get Name() As String
End Property

Public Property Get Crew() As Integer
End Property

Public Property Get Sensor() As Integer
End Property

Public Property Get OffStruct() As Integer
End Property

Public Property Let OffStruct(ByVal intOffStruct As Integer)
End Property

Public Property Get Prestige() As Integer
End Property

Public Property Let Prestige(ByVal intPrestige As Integer)
End Property
