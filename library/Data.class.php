<?php
/** Exemple de vrai singleton */

class Data {
	
	private $pdo;
	
	static private $instance;
	
	private function __construct()
	{
		$configuration = new Configuration();
			
		$this->pdo = new PDO
		(
			$configuration->get('database', 'dsn'),
			$configuration->get('database', 'user'),
			$configuration->get('database', 'password')
		);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->exec('SET NAMES UTF8');
	}
	
	public static function create()
	{
		if(!self::$instance instanceof Data)
		{
			self::$instance =  self::__construct();
		}
		return self::$instance;
	}
	
	
	/** Execute une requête sql de type Insert ou Update ou Delete
	 * 
	 * @param string $sql requête sql a executer
	 * @param array $values tableaux associatif ou indexé des valeurs à passer à l'execution de la requête
	 * @return integer dernier id enregistré dans la base
	 */
	public function executeSql($sql, array $values = array())
	{
		$query = $this->pdo->prepare($sql);

		$query->execute($values);

		return $this->pdo->lastInsertId();
	}

	/** Execute une requête sql de type Select et renvoi le jeu d'enregistrement complet
	 * 
	 * @param string $sql requête sql a executer
	 * @param array $values tableaux associatif ou indexé des valeurs à passer à l'execution de la requête
	 * @return array jeu d'enregistrement
	 */
    public function query($sql, array $criteria = array())
    {
        $query = $this->pdo->prepare($sql);

        $query->execute($criteria);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

	/** Execute une requête sql de type Select et renvoi la premièe ligne du jeu d'enregistrement
	 * 
	 * @param string $sql requête sql a executer
	 * @param array $values tableaux associatif ou indexé des valeurs à passer à l'execution de la requête
	 * @return array jeu d'enregistrement : la première ligne
	 */
    public function queryOne($sql, array $criteria = array())
    {
        $query = $this->pdo->prepare($sql);

        $query->execute($criteria);

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}


$db = Data::create();

$db = Data::create();