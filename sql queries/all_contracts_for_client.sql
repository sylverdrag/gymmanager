SELECT
    DATE(creation_date) AS "Date",
    contract_id AS "Contract ID", 
    training_type AS "Type",
    nb_sessions AS "Total Sessions",
    nb_sessions - remaining_sessions AS "Sessions done",
    remaining_sessions AS "Sessions remaining",
    nb_sessions * price_per_session AS "Total Price",
    DATE(expire_date) AS "Expires on..."
FROM 
    sylver_gymmngr.contracts
WHERE
    client_id = "clt_20140809_150843"
ORDER BY creation_date ASC
;
