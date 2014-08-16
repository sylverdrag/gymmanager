SELECT 
    sylver_gymmngr.sessions.`date` AS 'Date',
    CONCAT(sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client',
    CONCAT(sylver_gymmngr.contracts.training_type, ' (', sylver_gymmngr.contracts.remaining_sessions, ')') AS 'Contract',
    CONCAT(sylver_gymmngr.trainers.first_name, ' ', sylver_gymmngr.trainers.last_name) AS 'Trainer'
    
FROM
    sylver_gymmngr.clients
JOIN sylver_gymmngr.sessions ON sylver_gymmngr.sessions.client_id = sylver_gymmngr.clients.client_id
JOIN sylver_gymmngr.contracts ON sylver_gymmngr.sessions.contract_id = sylver_gymmngr.contracts.contract_id
JOIN sylver_gymmngr.trainers ON sylver_gymmngr.sessions.trainer_id = sylver_gymmngr.trainers.trainer_id
WHERE
    DATE(sessions.date) >= '2014-08-01' AND
    DATE(sessions.date) <= '2014-08-31'
ORDER BY sylver_gymmngr.sessions.`date` ASC
;
