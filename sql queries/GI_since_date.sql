/* Calculates the GI since a specific date */
select 
    SUM(nb_sessions * price_per_session) 
from 
    sylver_gymmngr.contracts
WHERE
    creation_date >= DATE("2014-08-01")
;
