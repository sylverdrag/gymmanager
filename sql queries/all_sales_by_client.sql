SELECT  
    sylver_gymmngr.contracts.creation_date AS 'Date',
    CONCAT (sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client',
    SUM(nb_sessions * price_per_session) AS 'Total',
    ' THB' AS 'Currency',
    training_type AS 'Training type'
FROM 
    sylver_gymmngr.contracts
JOIN sylver_gymmngr.clients
ON sylver_gymmngr.clients.client_id = sylver_gymmngr.contracts.client_id
GROUP BY sylver_gymmngr.clients.client_id
ORDER BY SUM(nb_sessions * price_per_session) DESC
;