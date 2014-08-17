SELECT
    DATE(date) AS 'Date',
    CONCAT(sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Name',
    sylver_gymmngr.sessions.`type` AS 'Type',
    sylver_gymmngr.trainers.first_name AS 'Trainer'
FROM 
    sylver_gymmngr.sessions
JOIN 
    sylver_gymmngr.clients ON sylver_gymmngr.sessions.client_id = sylver_gymmngr.clients.client_id
JOIN 
    sylver_gymmngr.trainers ON sylver_gymmngr.sessions.trainer_id = sylver_gymmngr.trainers.trainer_id
WHERE
    sylver_gymmngr.sessions.client_id = 'clt_20140816_190839'
ORDER BY date DESC;