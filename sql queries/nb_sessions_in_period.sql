SELECT  
    count(session_id)
FROM 
    sylver_gymmngr.sessions
WHERE
    DATE(sessions.date) >= "2014-08-01" AND
    DATE(sessions.date) <= "2014-08-31"
;