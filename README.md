# gymmanager
This is a gym manager app I wrote in PHP (v5) for the owner of couple fitness studios some years ago. 

The workflow was as follows:

Client comes in, is sold a contract refering to a training package (Pilates, Yoga, Boxing, TRX...) containing a certain number of hours and a personal trainer is assigned to the client, at a specific training location.
Each package has its own rate, discount and duration. Each time the client comes to train, the session is logged, which deducts the number of sessions available in the contract and increments the personal trainer's pay accordingly. 
The system produces reports on client activity, expiring contracts, income, trainer pay, etc. Trainers have a restricted login that allows them to log session for customers and nothing else, while the owner can create training packages and contracts, hire trainers, give discounts... 

The database tables are described in gymmngr_tables.sql. Login system is a bit primitive as it was intended for a handful of users on intranet.  
