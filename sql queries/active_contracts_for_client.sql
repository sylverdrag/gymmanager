SELECT
    CONCAT(sylver_gymmngr.clients.first_name, " ", sylver_gymmngr.clients.last_name) AS 'Name',
    contract_id AS "Contract ID", 
    training_type AS "Type",
    nb_sessions AS "Total Sessions",
    nb_sessions - remaining_sessions AS "Sessions done",
    remaining_sessions AS "Sessions remaining",
    nb_sessions * price_per_session AS "Total Price",
    DATE(expire_date) AS "Expires on..."
FROM 
    sylver_gymmngr.contracts
JOIN 
    sylver_gymmngr.clients ON sylver_gymmngr.contracts.client_id = sylver_gymmngr.clients.client_id
WHERE
    sylver_gymmngr.contracts.client_id = "clt_20140809_150843" AND
    remaining_sessions > 0 AND
    DATE(expire_date) > DATE(now())
ORDER BY expire_date ASC
;
