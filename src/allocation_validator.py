import json

def validate_trip(trip, vehicles_db, outlets_db):
    """
    Validates a single trip according to Hackathon rules (A2.3 Application Logic).
    
    trip: dict with {
        "trip_id": str,
        "vehicle_id": str,
        "orders": [
            {"order_ref": str, "brand": str, "district": str, "outlet_id": str, "weight_kg": float, "volume_m3": float, "temp_requirement": str}
        ]
    }
    """
    vehicle = vehicles_db.get(trip["vehicle_id"])
    if not vehicle:
        return {"valid": False, "reason": "Vehicle not found"}
        
    if not trip["orders"]:
        return {"valid": False, "reason": "Trip has no orders"}

    # 1. Brand and District Grouping Check
    trip_brand = trip["orders"][0]["brand"]
    trip_district = trip["orders"][0]["district"]
    
    for order in trip["orders"]:
        if order["brand"] != trip_brand:
            return {"valid": False, "reason": f"Mixed brands in trip: {trip_brand} and {order['brand']}"}
        if order["district"] != trip_district:
            return {"valid": False, "reason": f"Mixed districts in trip: {trip_district} and {order['district']}"}

    # 2. Capacity Check (Weight and Volume)
    total_weight = sum(o["weight_kg"] for o in trip["orders"])
    total_volume = sum(o["volume_m3"] for o in trip["orders"])
    
    if total_weight > vehicle["weight_cap_kg"]:
        return {"valid": False, "reason": f"Weight capacity exceeded: {total_weight} > {vehicle['weight_cap_kg']}"}
    if total_volume > vehicle["volume_cap_m3"]:
        return {"valid": False, "reason": f"Volume capacity exceeded: {total_volume} > {vehicle['volume_cap_m3']}"}

    # 3. Reefer Constraint Check
    needs_reefer = any(o["temp_requirement"] == "chilled" for o in trip["orders"])
    if needs_reefer and vehicle["temp"] != "reefer":
        return {"valid": False, "reason": "Chilled orders require a reefer vehicle"}

    # 4. Van Only Constraint Check
    for order in trip["orders"]:
        outlet = outlets_db.get(order["outlet_id"])
        if outlet and outlet["parking_constraint"] == "van_only" and vehicle["type"] != "van":
            return {"valid": False, "reason": f"Outlet {order['outlet_id']} requires a van"}

    # 5. Depot Matching Check
    for order in trip["orders"]:
        outlet = outlets_db.get(order["outlet_id"])
        if outlet and outlet["depot"] != vehicle["depot"]:
            return {"valid": False, "reason": f"Outlet {order['outlet_id']} depot ({outlet['depot']}) does not match vehicle depot ({vehicle['depot']})"}

    return {"valid": True, "reason": "Trip valid"}
