SELECT  
    COUNT(sessions.session_id),
    SUM(contracts.price_per_session)
FROM 
    sylver_gymmngr.sessions
JOIN 
    sylver_gymmngr.contracts
ON 
    sessions.contract_id = contracts.contract_id
WHERE
    DATE(sessions.date) >= "2014-07-01"
;