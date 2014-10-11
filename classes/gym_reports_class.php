<?php
/**
 * @namespace GymManager.session
 */

/**
 * Handles the loging of sessions in the system. 
 * Requires a PDO database handle 
 * @author: Sylvain Galibert
 */
class gym_reports_class
{
    ### class properties ###
    /**
    * Training session variables
    */
    private $session_id    = ""; // valid format is ses_yyyymmdd_hhmmss
    private $date          = "";
    private $client_id     = ""; // valid format is clt_yyyymmdd_hhmmss
    private $contract_id   = ""; // valid format is ctt_yyyymmdd_hhmmss
    private $trainer_id    = ""; // valid format is tnr_yyyymmdd_hhmmss
    private $type          = "";
    private $comments      = "";
    private $connect       = "";
    
    /**
     * Training package specific variables (a training package is a number of training sessions sold together)
     */
    public $package_id         = "";
    public $package_name       = "";
    public $nb_sessions        = "";
    public $price_per_session  = "";
    public $package_active     = "";
    public $package_discount   = "";
    public $package_comments   = "";
    
    /**
     * Client-specific variables
     */
    public $client_first_name  = "";
    public $client_last_name   = "";
    public $client_address     = "";
    public $client_city        = "";
    public $client_zip         = "";
    public $client_age         = "";
    public $client_sex         = "";
    public $client_phone       = "";
    public $client_email       = "";
    public $client_create_date = "";

    /**
     * Trainer-specific variables
     */
    public $trainer_first_name          = "";
    public $trainer_last_name           = "";
    public $trainer_address             = "";
    public $trainer_age                 = "";
    public $trainer_sex                 = "";
    public $trainer_phone               = "";
    public $trainer_is_active           = "";
    public $trainer_create_date         = "";
    public $trainer_rate_boxing         = "";
    public $trainer_rate_pilates        = "";
    public $trainer_rate_bodyweight     = "";
    public $trainer_rate_yoga           = "";
    public $trainer_rate_trx            = "";
    
    /**
     * contract-specific variables
     */
    public $contract_creation_date      = "";
    public $branch                      = "";
    public $start_date                  = "";
    public $expire_date                 = "";
    public $remaining_sessions          = "";
    public $trainer_rate_modifier       = "";


    ### Constructor ###
    function __construct($dbLink)
    {
        $this->connect  = $dbLink;
    }

    ### Reporting methods - Getting data for reports ###
    /*
     * Calculate the GI from a from date to a to date, inclusive, 
     * based on the contracts' creation date. The assumption here is that contracts are always paid when they are entered in the computer.
     * Returns the GI as a non formatted numerical string.
     */
    function calculate_GI($from_date, $to_date)
    {
        try {
            $stmt = $this->connect->prepare("/* Calculates the GI since a specific date */
                    SELECT 
                        SUM(nb_sessions * price_per_session) AS 'GI'
                    FROM 
                        sylver_gymmngr.contracts
                    WHERE
                        creation_date >= DATE(:from_date) AND
                        creation_date <= DATE(:to_date)
                    ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                $results =  $stmt->fetch();
                return $results["GI"];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * Calculate the VSD from a from date to a to date, inclusive, 
     * based on the sessions date. 
     * Returns the GI as a non formatted numerical string.
     */
    function calculate_VSD($from_date, $to_date)
    {
        try {
            $stmt = $this->connect->prepare("/* Calculates the GI since a specific date */
                    SELECT  
                        SUM(contracts.price_per_session) AS 'VSD'
                    FROM 
                        sylver_gymmngr.sessions
                    JOIN 
                        sylver_gymmngr.contracts
                    ON 
                        sessions.contract_id = contracts.contract_id
                    WHERE
                        DATE(sessions.date) >= DATE(:from_date) AND
                        DATE(sessions.date) <= DATE(:to_date)
                    ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                $results = $stmt->fetch();
                return $results["VSD"];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * List contracts sold over a period and the name of the clients who bought them. Accepts a limit variable.
     * Returns an array containing the date of the contract, the full name of the client, the contract ID, 
     * the total value of the contract and the type of training.
     */
    function get_sales($from_date, $to_date, $limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List contracts sold over a period and the name of the clients who bought them */
                        SELECT  
                            CONCAT (DATE(sylver_gymmngr.contracts.creation_date), ' ' ,TIME(sylver_gymmngr.contracts.creation_date)) AS 'Date',
                            CONCAT (sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client',
                            sylver_gymmngr.contracts.contract_id AS 'Contract ID',
                            FORMAT(nb_sessions * price_per_session, 0) AS 'THB',
                            training_type AS 'Training type'
                        FROM 
                            sylver_gymmngr.contracts
                        JOIN sylver_gymmngr.clients
                        ON sylver_gymmngr.clients.client_id = sylver_gymmngr.contracts.client_id
                        WHERE
                            creation_date >= DATE(:from_date) AND
                            creation_date <= DATE(:to_date)
                        $limit
                        ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * List contracts sold over a period and the name of the clients who bought them. Accepts a limit variable.
     * Returns an array containing the date of the contract, the full name of the client, the contract ID, 
     * the total value of the contract and the type of training.
     */
    function get_sales_by_branch($from_date, $to_date, $branch, $limit)
    {
        if ($branch === "all")
        {
            $branch = "%" ;
        }
        try {
                $stmt = $this->connect->prepare("/* List contracts sold over a period and the name of the clients who bought them */
                        SELECT  
                            CONCAT (DATE(sylver_gymmngr.contracts.creation_date), ' ' ,TIME(sylver_gymmngr.contracts.creation_date)) AS 'Date',
                            CONCAT (sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client',
                            sylver_gymmngr.contracts.branch AS 'Branch',
                            FORMAT(nb_sessions * price_per_session, 0) AS 'THB',
                            training_type AS 'Training type'
                        FROM 
                            sylver_gymmngr.contracts
                        JOIN sylver_gymmngr.clients
                        ON sylver_gymmngr.clients.client_id = sylver_gymmngr.contracts.client_id
                        WHERE
                            creation_date >= DATE(:from_date) AND
                            creation_date <= DATE(:to_date) AND
                            branch LIKE :branch
                        $limit
                        ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            $stmt->bindParam(':branch', $branch);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * List active contracts for a specific client.
     * Returns an array 
     */
    function get_active_contracts_for_client($client_id, $limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List active contracts for a specific client */
                        SELECT
                            CONCAT(sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Name',
                            contract_id AS 'Contract ID', 
                            training_type AS 'Type',
                            nb_sessions AS 'Total Sessions',
                            nb_sessions - remaining_sessions AS 'Sessions done',
                            remaining_sessions AS 'Sessions remaining',
                            FORMAT(nb_sessions * price_per_session, 0) AS 'Total Price',
                            DATE(expire_date) AS 'Expires on...'
                        FROM 
                            sylver_gymmngr.contracts
                        JOIN 
                            sylver_gymmngr.clients ON sylver_gymmngr.contracts.client_id = sylver_gymmngr.clients.client_id
                        WHERE
                            sylver_gymmngr.contracts.client_id = :client_id AND
                            remaining_sessions > 0 AND
                            DATE(expire_date) > DATE(now())
                        ORDER BY expire_date ASC
                        $limit
                        ;");
            $stmt->bindParam(':client_id', $client_id);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * List all contracts ever purchased by a specific client.
     * Returns an array 
     */
    function get_all_contracts_for_client($client_id, $limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List all contracts for a specific client */
                        SELECT
                            CONCAT(sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Name',
                            DATE(creation_date) AS 'Date',
                            contract_id AS 'Contract ID', 
                            training_type AS 'Type',
                            nb_sessions AS 'Total Sessions',
                            nb_sessions - remaining_sessions AS 'Sessions done',
                            remaining_sessions AS 'Sessions remaining',
                            FORMAT(nb_sessions * price_per_session,0) AS 'Total Price',
                            DATE(expire_date) AS 'Expires on...'
                        FROM 
                            sylver_gymmngr.contracts
                        JOIN 
                            sylver_gymmngr.clients ON sylver_gymmngr.contracts.client_id = sylver_gymmngr.clients.client_id
                        WHERE
                            sylver_gymmngr.contracts.client_id = :client_id 
                        ORDER BY creation_date ASC
                        $limit
                        ;");
            $stmt->bindParam(':client_id', $client_id);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * List all sessions ever done by a specific client.
     * Returns an array 
     */
    function get_all_sessions_for_client($client_id, $limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List all contracts for a specific client */
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
                            sylver_gymmngr.sessions.client_id = :client_id 
                        ORDER BY date DESC;
                        $limit
                        ;");
            $stmt->bindParam(':client_id', $client_id);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }


    /*
     * Contact details of a specific client.
     * Returns an array 
     */
    function get_client_details($client_id)
    {
        try {
                $stmt = $this->connect->prepare("/* Details of a specific client */
                        SELECT
                            * 
                        FROM 
                            sylver_gymmngr.clients 
                        WHERE
                            sylver_gymmngr.clients.client_id = :client_id 
                        ;");
            $stmt->bindParam(':client_id', $client_id);
            if ($stmt->execute())
            {
                $client_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $client_details[0];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Contact details of a specific trainer.
     * Returns an array 
     */
    function get_trainer_details($trainer_id)
    {
        try {
                $stmt = $this->connect->prepare("/* Details of a specific client */
                        SELECT
                            * 
                        FROM 
                            sylver_gymmngr.trainers 
                        WHERE
                            sylver_gymmngr.trainers.trainer_id = :trainer_id 
                        ;");
            $stmt->bindParam(':trainer_id', $trainer_id);
            if ($stmt->execute())
            {
                $trainer_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $trainer_details[0];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    
    /*
     * List active contracts for a specific client.
     * Returns an array 
     */
    function get_all_sales_by_client($limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List total purchases for clients */
                        SELECT  
                            sylver_gymmngr.contracts.creation_date AS 'Date',
                            CONCAT (sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client',
                            FORMAT(SUM(nb_sessions * price_per_session),0) AS 'Total',
                            training_type AS 'Training type'
                        FROM 
                            sylver_gymmngr.contracts
                        JOIN sylver_gymmngr.clients
                        ON sylver_gymmngr.clients.client_id = sylver_gymmngr.contracts.client_id
                        GROUP BY sylver_gymmngr.clients.client_id
                        ORDER BY SUM(nb_sessions * price_per_session) DESC
                        $limit
                        ;");
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    function clients_sessions_in_period($from_date, $to_date, $limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List clients session in the period given */
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
                            DATE(sessions.date) >= DATE(:from_date) AND
                            DATE(sessions.date) <= DATE(:to_date)
                        ORDER BY sylver_gymmngr.sessions.`date` ASC
                        $limit
                        ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Calculate the number of sessions done from a from date to a to date, inclusive, 
     * Returns the number of sessions.
     */
    function get_nb_sessions_in_period($from_date, $to_date)
    {
        try {
            $stmt = $this->connect->prepare("/* Calculates the GI since a specific date */
                    SELECT  
                        count(session_id) AS 'Sessions'
                    FROM 
                        sylver_gymmngr.sessions
                    WHERE
                        DATE(sessions.date) >= DATE(:from_date) AND
                        DATE(sessions.date) <= DATE(:to_date)
                    ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                $results = $stmt->fetch();
                return $results["Sessions"];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * List all sessions given by a specific trainer during a time period
     * RETURNS: Array
     */
    function trainer_activity_in_period($trainer_id, $from_date, $to_date, $limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List activity in the period given for a specific trainer */
                        SELECT 
                            sylver_gymmngr.sessions.`date` AS 'Date', 
                            CONCAT(sylver_gymmngr.trainers.first_name, ' ', sylver_gymmngr.trainers.last_name) AS 'Trainer',
                            sylver_gymmngr.sessions.`type` AS 'Training type',
                            CONCAT(sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client',
                            CASE
                                WHEN sylver_gymmngr.sessions.`type` = 'Pilates'
                                    THEN sylver_gymmngr.trainers.pilates_rate
                                WHEN sylver_gymmngr.sessions.`type` = 'TRX'
                                    THEN sylver_gymmngr.trainers.trx_rate
                                WHEN sylver_gymmngr.sessions.`type` = 'Boxing'
                                    THEN sylver_gymmngr.trainers.boxing_rate
                                WHEN sylver_gymmngr.sessions.`type` = 'Bodyweight'
                                    THEN sylver_gymmngr.trainers.bodyweight_rate
                                WHEN sylver_gymmngr.sessions.`type` = 'Yoga'
                                    THEN sylver_gymmngr.trainers.yoga_rate
                            END AS 'Training rate',
                            sylver_gymmngr.contracts.trainer_rate_modifier AS 'Premium',
                            CASE
                                WHEN sylver_gymmngr.sessions.`type` = 'Pilates'
                                    THEN sylver_gymmngr.trainers.pilates_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'TRX'
                                    THEN sylver_gymmngr.trainers.trx_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Boxing'
                                    THEN sylver_gymmngr.trainers.boxing_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Bodyweight'
                                    THEN sylver_gymmngr.trainers.bodyweight_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Yoga'
                                    THEN sylver_gymmngr.trainers.yoga_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                            END AS 'Total'
                        FROM 
                            sylver_gymmngr.sessions
                        JOIN
                            sylver_gymmngr.trainers ON sylver_gymmngr.sessions.trainer_id = sylver_gymmngr.trainers.trainer_id
                        JOIN
                            sylver_gymmngr.clients ON sylver_gymmngr.sessions.client_id = sylver_gymmngr.clients.client_id
                        JOIN
                            sylver_gymmngr.contracts ON sylver_gymmngr.sessions.contract_id = sylver_gymmngr.contracts.contract_id
                        WHERE
                            DATE(sessions.date) >= DATE(:from_date) AND
                            DATE(sessions.date) <= DATE(:to_date) AND
                            sylver_gymmngr.trainers.trainer_id = :trainer_id
                        ORDER BY sylver_gymmngr.sessions.`date`
                        $limit
                        ;");
            $stmt->bindParam(':trainer_id', $trainer_id);
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Calculate the fees of a specific trainer from a from date to a to date, inclusive, 
     * Returns the number of sessions.
     */
    function calculate_trainer_fees($trainer_id, $from_date, $to_date)
    {
        try {
            $stmt = $this->connect->prepare("/* Calculate the fees of a specific trainer for the given period */
                    SELECT 
                        SUM(
                        CASE
                            WHEN sylver_gymmngr.sessions.`type` = 'Pilates'
                                THEN sylver_gymmngr.trainers.pilates_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                            WHEN sylver_gymmngr.sessions.`type` = 'TRX'
                                THEN sylver_gymmngr.trainers.trx_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                            WHEN sylver_gymmngr.sessions.`type` = 'Boxing'
                                THEN sylver_gymmngr.trainers.boxing_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                            WHEN sylver_gymmngr.sessions.`type` = 'Bodyweight'
                                THEN sylver_gymmngr.trainers.bodyweight_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                            WHEN sylver_gymmngr.sessions.`type` = 'Yoga'
                                THEN sylver_gymmngr.trainers.yoga_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                        END) AS 'Fee'

                    FROM 
                        sylver_gymmngr.sessions
                    JOIN
                        sylver_gymmngr.trainers ON sylver_gymmngr.sessions.trainer_id = sylver_gymmngr.trainers.trainer_id
                    JOIN
                        sylver_gymmngr.contracts ON sylver_gymmngr.sessions.contract_id = sylver_gymmngr.contracts.contract_id
                    WHERE
                        DATE(sessions.date) >= DATE(:from_date) AND
                        DATE(sessions.date) <= DATE(:to_date) AND
                        sylver_gymmngr.trainers.trainer_id = :trainer_id
                    ;");
            $stmt->bindParam(':trainer_id', $trainer_id);
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                $result = $stmt->fetch();
                return $result["Fee"];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * List all sessions given by all trainer during a time period
     * RETURNS: Array
     */
    function all_trainer_activity_in_period($from_date, $to_date, $limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List activity in the period given for all trainers */
                        SELECT 
                            sylver_gymmngr.sessions.`date` AS 'Date', 
                            CONCAT(sylver_gymmngr.trainers.first_name, ' ', sylver_gymmngr.trainers.last_name) AS 'Trainer',
                          /*  sylver_gymmngr.sessions.contract_id AS 'Contract ID', */
                            sylver_gymmngr.sessions.`type` AS 'Training type',
                            CONCAT(sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client',
                            CASE
                                WHEN sylver_gymmngr.sessions.`type` = 'Pilates'
                                    THEN sylver_gymmngr.trainers.pilates_rate
                                WHEN sylver_gymmngr.sessions.`type` = 'TRX'
                                    THEN sylver_gymmngr.trainers.trx_rate
                                WHEN sylver_gymmngr.sessions.`type` = 'Boxing'
                                    THEN sylver_gymmngr.trainers.boxing_rate
                                WHEN sylver_gymmngr.sessions.`type` = 'Bodyweight'
                                    THEN sylver_gymmngr.trainers.bodyweight_rate
                                WHEN sylver_gymmngr.sessions.`type` = 'Yoga'
                                    THEN sylver_gymmngr.trainers.yoga_rate
                            END AS 'Training rate',
                            sylver_gymmngr.contracts.trainer_rate_modifier AS 'Premium',
                            CASE
                                WHEN sylver_gymmngr.sessions.`type` = 'Pilates'
                                    THEN sylver_gymmngr.trainers.pilates_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'TRX'
                                    THEN sylver_gymmngr.trainers.trx_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Boxing'
                                    THEN sylver_gymmngr.trainers.boxing_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Bodyweight'
                                    THEN sylver_gymmngr.trainers.bodyweight_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Yoga'
                                    THEN sylver_gymmngr.trainers.yoga_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                            END AS 'Total'
                        FROM 
                            sylver_gymmngr.sessions
                        JOIN
                            sylver_gymmngr.trainers ON sylver_gymmngr.sessions.trainer_id = sylver_gymmngr.trainers.trainer_id
                        JOIN
                            sylver_gymmngr.clients ON sylver_gymmngr.sessions.client_id = sylver_gymmngr.clients.client_id
                        JOIN
                            sylver_gymmngr.contracts ON sylver_gymmngr.sessions.contract_id = sylver_gymmngr.contracts.contract_id
                        WHERE
                            DATE(sessions.date) >= DATE(:from_date) AND
                            DATE(sessions.date) <= DATE(:to_date)
                        ORDER BY sylver_gymmngr.sessions.`date`
                        $limit
                        ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Calculate the total fee of all trainers from a from date to a to date, inclusive. 
     * (When taken out of the GI, gives the raw profit -not accounting for other running expenses)
     * Returns the number of sessions.
     */
    function calculate_total_trainer_fees($from_date, $to_date)
    {
        try {
            $stmt = $this->connect->prepare("
                    /* Computes the total fees for all trainers. 
                     * Important: if Vinus has a fee as a trainer, her fees will be added to this total - The easiest work-around is to put her rate as '0'. 
                     * This might conflict if the contract specifies a trainer premium (test and figure out if it needs to be taken into account)
                     */
                    SELECT 
                        SUM(
                            CASE
                                WHEN sylver_gymmngr.sessions.`type` = 'Pilates'
                                    THEN sylver_gymmngr.trainers.pilates_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'TRX'
                                    THEN sylver_gymmngr.trainers.trx_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Boxing'
                                    THEN sylver_gymmngr.trainers.boxing_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Bodyweight'
                                    THEN sylver_gymmngr.trainers.bodyweight_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                                WHEN sylver_gymmngr.sessions.`type` = 'Yoga'
                                    THEN sylver_gymmngr.trainers.yoga_rate + sylver_gymmngr.contracts.trainer_rate_modifier
                            END
                        ) AS 'Total'

                    FROM 
                        sylver_gymmngr.sessions
                    JOIN
                        sylver_gymmngr.trainers ON sylver_gymmngr.sessions.trainer_id = sylver_gymmngr.trainers.trainer_id
                    JOIN
                        sylver_gymmngr.contracts ON sylver_gymmngr.sessions.contract_id = sylver_gymmngr.contracts.contract_id
                    WHERE
                        DATE(sessions.date) >= DATE(:from_date) AND
                        DATE(sessions.date) <= DATE(:to_date)
                    ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                $result = $stmt->fetch();
                return $result["Total"];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * Count the days since last session for all clients.
     * Clients who haven't been training for the longest period show on top.
     * Clients who haven't trained for less than $min or more than $max are ignored
     * (Looking for clients gone AWOL here)
     */
    function days_since_last_session($min, $max, $limit)
    {
        try {
                $stmt = $this->connect->prepare("
                        SELECT
                            DATE(sylver_gymmngr.sessions.`date`) AS 'Date',    
                            MIN(DATEDIFF(DATE(now()), DATE(sylver_gymmngr.sessions.`date`))) AS 'Days since last session',
                            CONCAT(sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client'    
                        FROM 
                            sylver_gymmngr.sessions
                        JOIN
                            sylver_gymmngr.clients ON 
                            sylver_gymmngr.sessions.client_id = sylver_gymmngr.clients.client_id
                        GROUP BY 
                            sylver_gymmngr.sessions.client_id
                        HAVING
                            MIN(DATEDIFF(DATE(now()), DATE(sylver_gymmngr.sessions.`date`))) >= :min AND
                            MIN(DATEDIFF(DATE(now()), DATE(sylver_gymmngr.sessions.`date`))) <= :max
                        ORDER BY 
                            MIN(DATEDIFF(DATE(now()), DATE(sylver_gymmngr.sessions.`date`))) DESC
                        $limit
                        ;");
            $stmt->bindParam(':min', $min);
            $stmt->bindParam(':max', $max);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    

    
    
### Formatting results ###
    /*
     * Format a result set in a table.
     * $caption sets the table's caption
     * $ignore_cols is an array of columns to ignore from the result set 
     * (This is used when a specific column data should be hidden from the table, but is used somewhere else - 
     * ex: when getting the contracts of a client, the client name is required for the title, but there is no point putting it in the result table.)
     * RETURNS: HTML Table with the result set and all classes/IDs necessary for proper formatting.
     * TODO: Enable further processing (like sorting, filtering, etc.)
     */
    function results_to_table ($results_array, $report_name, $caption = "", $ignore_cols = "")
    {
        $table = "<table id='". $report_name ."' class='tbl_report' cellspacing='0'>\n
                    <thead>\n
                        <tr>\n";
        $table_headers = "";
        if (is_array($results_array[0]))
        {
            foreach ($results_array[0] as $key => $value)
            {
                if (!in_array($key, $ignore_cols))
                {
                    $table_headers .= "<td id='$key' class='tbl_header_cell'>" . $key . "</td>\n";
                }
            }
            $table .= $table_headers . "</tr>\n</thead>\n<tbody>\n";
            for ($i = 0; $i < count($results_array); $i++)
            {
                $table_rows .= "<tr>\n";
                foreach ($results_array[$i] as $key => $value)
                {
                    if (!in_array($key, $ignore_cols)) 
                    {
                        if ($i%2 == 0)
                        {
                            $row .= "<td class='$key tbl_data_cell_even'>" . $value . "</td>";
                        }
                        else 
                        {
                            $row .= "<td class='$key tbl_data_cell_odd'>" . $value . "</td>";
                        }
                    }
                }
                $table_rows .= $row . "\n</tr>\n";
                $row = "";
            }
            $table .= $table_rows . "\n</tbody>\n<caption>$caption</caption>\n</table>\n";
            return $table;
        }
        else 
        {
            return false;
        }
    }


### Utilities
        
/**
* Check if the string is a valid ID (like ses_20140725_183521)
*/
private function is_valid_id($sInput)
{
    $pattern = "/^.{3}_\d{8}_\d{6}$/";
    if (preg_match($pattern, $sInput) ==1){
            return true;
    } else {
            return false;
    }
}


### Getters and Setters ###
	
    /**
     * Getters
     */
    public function get_session_id()
    {
        return $this->session_id;
    }
    public function get_session_date()
    {
        return $this->date;
    }
    public function get_client_id()
    {
        return $this->client_id;
    }
    public function get_contract_id()
    {
        return $this->contract_id;
    }
    public function get_trainer_id()
    {
        return $this->trainer_id;
    }
    public function get_session_type()
    {
        return $this->type;
    }
    public function get_comments()
    {
        return $this->comments;
    }

    /**
     * Setters 
     */
    public function set_session_id($ID)
    {
    	if ($this->is_valid_id($ID))
        {
            $this->session_id = $ID;	
            return true;		
        }else { 
            return false;
        }        
    }
    public function set_session_date($date)
    {
        $this->date = $date;
    }
    public function set_client_id($ID)
    {
        if ($this->is_valid_id($ID))
        {
            $this->client_id = $ID;
            return true;		
        }else { 
            return false;
        }    
    }
    public function set_contract_id($ID)
    {
        if ($this->is_valid_id($ID))
        {
            $this->contract_id = $ID;
            return true;		
        }else { 
            return false;
        }   
    }
    public function set_trainer_id($ID)
    {
        if ($this->is_valid_id($ID))
        {
            $this->trainer_id = $ID;
            return true;		
        }else { 
            return false;
        }   
    }
    public function set_session_type($training_type)
    {
        $this->type = $training_type;
    }
    public function set_comments($session_comments)
    {
        $this->comments = $session_comments;
    }
}