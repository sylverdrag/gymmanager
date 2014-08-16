/* List contracts sold over a period and the name of the clients who bought them */
SELECT  
    CONCAT (DATE(sylver_gymmngr.contracts.creation_date), " ",TIME(sylver_gymmngr.contracts.creation_date)) AS "Date",
    CONCAT (sylver_gymmngr.clients.first_name, " ", sylver_gymmngr.clients.last_name) AS "Client",
    sylver_gymmngr.contracts.contract_id AS "Contract ID",
    nb_sessions * price_per_session AS "Amount",
    training_type AS "Training type"
FROM 
    sylver_gymmngr.contracts
JOIN sylver_gymmngr.clients
ON sylver_gymmngr.clients.client_id = sylver_gymmngr.contracts.client_id
WHERE
    creation_date >= DATE("2014-07-01") AND
    creation_date <= DATE("2014-08-31")
;