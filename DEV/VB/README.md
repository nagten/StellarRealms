## Stellar Realms Turn Counter ([SRTC](https://github.com/nagten/StellarRealms/tree/master/DEV/VB/SRTC))

The tool that started it all in 2005.<br><br>
Stellar Realms is based on turns, one represents 20 minutes. This tool will help plan research, intelligence, attacks, etc because it calculates the completion time.

![SRTCPC](/images/SRTC/SRTC_PC.jpg)<br>
![SRTCMAC](/images/SRTC/SRTC_Mac.jpg)

## Stellar Realms Speed Counter ([SRSP](https://github.com/nagten/StellarRealms/tree/master/DEV/VB/SRSP))

Calculates time required to attack another planet, flight time of ships is determined by slowest ship, research, location and jump structure.

![SRSP](/images/SRSP/SRSP.jpg)<br>
![SRSP2](/images/SRSP/SRSPLocation.jpg)

## Stellar Realms Battle Engine ([SRSBattleEngine](https://github.com/nagten/StellarRealms/tree/master/DEV/VB/BattleEngine))

Reverse engineered the Stellar Realms battle code. This way we could simulate battles between fleets and thus predict outcomes of future battles. I even found some bugs in the real battle code and reported those to the makers of Stellar Realms.

![SRSBattleEngine](/images/BattleEngine/BattleEngine.jpg)

Two battle formula's can be used (Base and Metallikov) they can be configured in the UI or via the battlesim.ini file

```
[Options]
; 0 for base model, 1 for "Metallikov's"
BattleFormula=0

; Random factor is the amount of additional "random" damage done when offense is close defense
; ... reasonable values for random factor seem to be 2.5 - 3.0

RandomFactor=3

; RandomDropOffRatio is the rate at which offense is no longer considered to be close to defense
; ... reasonable values seem to be from 3-6, and generally higher values for RandomDropOffRatio
;  ... should be associated with higher values for RandomFactor
RandomDropOffRatio=6

; A reasonable range of values for simulation seems to be 2-5
CriticalHitRatio=3

; Algorithm follows --
; -----------------------------------------------
; Cases
; 1. Offense * 1.2 >= Defense ..... damage = (offense - defense) + 3 * (defense/offense) ^ 6
; 2. Offense * 1.2 < Defense .... damage = 1, unless Offense < Defense/4.5 in which case damage = 0.2
;
; Variables
; total_damage
; base_damage
; additional_damage_ratio
;
; random_factor = (parameter set via .ini file)
; random_dropoff_rate = (parameter set via .ini file)
; critical_hit_ratio = (parameter set via .ini file)
; Pseudo code:
; CASE 1 : Offense * 1.2 >= Defense
; base_damage = offense - defense
; If base_damage < 0 then base damage = 0
; additional_damage_ratio = defense / offense
; If additional_damage_ratio > 1.0 then additional_damage_ratio = 1.0
; total_damage = base_damage + (random_factor)*(additional_damage_ratio)^random_dropoff_rate
; If total_damage < 1 then total_damage = 1.0
; 
; CASE 2 : Offense * 1.2 < Defense
; total_damage = 1.0
; if offense < defense / critical_hit_ratio then total damage = 0.2
; 

Speed=30
JumpStructure=2
```
