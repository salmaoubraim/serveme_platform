# -*- coding: utf-8 -*-
"""Point de collecte avec coordonnées et bennes (2 ou 3 par point, 150-600 kg)."""
from __future__ import annotations
import math
from typing import List, Optional


class Benne:
    """Une benne à un point de collecte (poids entre 150 et 600 kg)."""
    __slots__ = ('id', 'poids_kg')

    def __init__(self, id_benne: int, poids_kg: float):
        if not 150 <= poids_kg <= 600:
            raise ValueError(f"poids_kg doit être entre 150 et 600, reçu {poids_kg}")
        self.id = id_benne
        self.poids_kg = poids_kg


class PointCollecte:
    """Point de collecte avec coordonnées et 2 ou 3 bennes."""
    __slots__ = ('id', 'x', 'y', 'nom', 'bennes')

    def __init__(
        self,
        id_point: int,
        x: float,
        y: float,
        nom: str = "",
        bennes: Optional[List[Benne]] = None
    ):
        self.id = id_point
        self.x = x
        self.y = y
        self.nom = nom or f"Point {id_point}"
        self.bennes = bennes or []

    def poids_total(self) -> float:
        """Poids total des bennes au point (kg)."""
        return sum(b.poids_kg for b in self.bennes)

    def distance_vers(self, autre: "PointCollecte") -> float:
        """Distance euclidienne vers un autre point."""
        return math.sqrt((self.x - autre.x) ** 2 + (self.y - autre.y) ** 2)

    def to_dict(self) -> dict:
        return {
            "id": self.id,
            "x": self.x,
            "y": self.y,
            "nom": self.nom,
            "poids_total": self.poids_total(),
            "nb_bennes": len(self.bennes),
            "bennes": [{"id": b.id, "poids_kg": b.poids_kg} for b in self.bennes],
        }
