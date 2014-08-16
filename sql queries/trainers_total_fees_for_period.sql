/* Computes the total fees for all trainers. 
 * Important: if Vinus has a fee as a trainer, her fees will be added to this total - The easiest work-around is to put her rate as "0". 
 * This might conflict if the contract specifies a trainer premium (test and figure out if it needs to be taken into account)
 */
SELECT 
    SUM(
        CASE
            WHEN sylver_gymmngr.sessions.`type` = "Pilates"
                THEN sylver_gymmngr.trainers.pilates_rate + sylver_gymmngr.contracts.trainer_rate_modifier
            WHEN sylver_gymmngr.sessions.`type` = "TRX"
                THEN sylver_gymmngr.trainers.trx_rate + sylver_gymmngr.contracts.trainer_rate_modifier
            WHEN sylver_gymmngr.sessions.`type` = "Boxing"
                THEN sylver_gymmngr.trainers.boxing_rate + sylver_gymmngr.contracts.trainer_rate_modifier
            WHEN sylver_gymmngr.sessions.`type` = "Bodyweight"
                THEN sylver_gymmngr.trainers.bodyweight_rate + sylver_gymmngr.contracts.trainer_rate_modifier
            WHEN sylver_gymmngr.sessions.`type` = "Yoga"
                THEN sylver_gymmngr.trainers.yoga_rate + sylver_gymmngr.contracts.trainer_rate_modifier
        END
    ) AS "Total trainers fees"
    
FROM 
    sylver_gymmngr.sessions
JOIN
    sylver_gymmngr.trainers ON sylver_gymmngr.sessions.trainer_id = sylver_gymmngr.trainers.trainer_id
JOIN
    sylver_gymmngr.contracts ON sylver_gymmngr.sessions.contract_id = sylver_gymmngr.contracts.contract_id
WHERE
    DATE(sessions.date) >= "2014-07-01" AND
    DATE(sessions.date) <= "2014-08-31" 
;
