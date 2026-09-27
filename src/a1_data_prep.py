import pandas as pd
import numpy as np
import os
import json

def run_a1_prep():
    data_dir = r"d:\Projects\rootcode\Tech-Triathlon 2026 - Datasets\data"
    
    print("Loading data...")
    outlets = pd.read_csv(os.path.join(data_dir, "General Data", "outlets.csv"))
    vehicles = pd.read_csv(os.path.join(data_dir, "General Data", "vehicles.csv"))
    
    # 1. Fleet Matrix
    print("\n--- A1.1 Fleet Intelligence Matrix ---")
    fleet_summary = vehicles.groupby(['depot', 'type', 'temp']).agg(
        count=('vehicle_id', 'count'),
        avg_weight_cap=('weight_cap_kg', 'mean'),
        avg_volume_cap=('volume_cap_m3', 'mean')
    )
    print(fleet_summary)
    
    # 2. Outlet Geography
    print("\n--- A1.2 Outlet Geography Analysis ---")
    outlet_summary = outlets.groupby(['depot', 'district', 'brand']).agg(
        total=('outlet_id', 'count'),
        van_only=('parking_constraint', lambda x: (x == 'van_only').sum()),
        mall_dock=('parking_constraint', lambda x: (x == 'mall_dock').sum())
    )
    print(outlet_summary)
    
    # 3. Peak Day Availability (S1)
    s1_fleet = pd.read_csv(os.path.join(data_dir, "Test Data", "task2b_peak_day_fleet.csv"))
    available_s1 = s1_fleet[s1_fleet['status'] == 'available']
    
    s1_vehicles = vehicles[vehicles['vehicle_id'].isin(available_s1['vehicle_id'])]
    print("\n--- S1 Peak Day Available Fleet ---")
    print(s1_vehicles.groupby(['type', 'temp']).size())
    
    # Save processed summaries
    print("\nSaving clean data structures to JSON...")
    outlets_dict = outlets.set_index('outlet_id').to_dict(orient='index')
    vehicles_dict = vehicles.set_index('vehicle_id').to_dict(orient='index')
    
    with open(os.path.join("d:\\Projects\\rootcode\\src", "outlets_profile.json"), "w") as f:
        json.dump(outlets_dict, f, indent=2)
        
    with open(os.path.join("d:\\Projects\\rootcode\\src", "vehicles_profile.json"), "w") as f:
        json.dump(vehicles_dict, f, indent=2)
        
    print("Done. Cleaned data ready for Hackathon and Datathon.")

if __name__ == "__main__":
    run_a1_prep()
