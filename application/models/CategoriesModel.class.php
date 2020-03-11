<?php

class CategoriesModel
{

    /**
     * @var Database Objet Database pour effectuer des requête
     */
    private $dbh;

    /**
     * @var string Database table utilisée pour les requête
     */
    private $table;

    /**  Constructeur
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->dbh = new Database();
        $this->table = 'category';
    }

    /** Retourne un tableau de toutes les catégories en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant toutes les catégories en base
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT c1.id, c1.title, c1.description, c2.title as parent, c1.parentId, COUNT(p.postId) as post FROM '.$this->table.' c1 LEFT JOIN '.$this->table.' c2 ON c1.parentId = c2.id LEFT JOIN post_category p ON c1.id = p.categoryId GROUP BY c1.id,c2.id ORDER BY c1.title, c1.parentId');
    }

    /** Ajoute une catégorie en base
     *
     * @param string $title nom de la catégorie
     * @param string $description description de la cétégorie
     * @param int $parentId
     */
    public function add($title, $description, $parentId) 
    {   $slug = $title;
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (title, description, slug, parentId) VALUES (?,?,?,?)',[$title, $description, $slug, $parentId]);
    }

    /** Trouve une catégorie avec son ID
     *
     * @param integer $id identifiant de la catégorie
     * @return Array Jeu d'enregistrement comportant la catégorie trouvée
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE id = ?',[$id]);
    }

   
    /** Modifie une catégorie en base
     *
     * @param integer $id identifiant de la catégorie
     * @param string $title nom de la catégorie
     * @param string $description description de la cétégorie
     * @param int $parentId
     * @return void
     */
    public function update($title, $description, $parentId, $id)
    {
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET title=?, description=?, parentId=? WHERE id=?',[$title, $description, $parentId, $id]); 
    }

    /** Supprime une catégorie avec son ID
     *
     * @param integer $id identifiant de la catégorie
     * @return void
     */
    public function delete($id)
    {
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE id=?',[$id]);
    }

    /** Function récursive (qui s'appelle elle même) permettant de trier le tableau des catégories
 * C'est un exercice algorithmique. Principe de récursivité
 * @param array $categories le tableau (jeu d'enregistrement) des catégories
 * @param mixed $parent l'id du parent s'il existe ou null
 */
function orderCategories($categories,$parent=null)
{
    $tree = array();

    foreach($categories as $index=>$categorie)
    {
        //var_dump($categorie['cat_parent'].' '.$parent);
        if($categorie['parentId']==$parent)
        {
            $childrens = $this->orderCategories($categories,$categorie['id']);
            if(count($childrens)>0)
                $categorie['childrens'] = $childrens;
            //var_dump($categorie);
            $tree[] = $categorie;
        }
        
    }

    return $tree;
}

    /** Function récursive (qui s'appelle elle même) permettant de trier le tableau des catégories
     * Cette fonction de créée pas de sous tableau mais donne un niveau de hérarchie et ordonne le tableau
     * @param array $categories le tableau (jeu d'enregistrement) des catégories
     * @param mixed $parent l'id du parent s'il existe ou null
     * @param mixed $level le niveau de hiérarchie
     */
    function orderCategoriesLevel($categories,$parent=null,$level=0)
    {
        $tree = array();
        foreach($categories as $index=>$categorie)
        {
            if($categorie['parentId']==$parent)
            {
                $categorie['level'] = $level;
                $tree[] = $categorie;
                $childrens = $this->orderCategoriesLevel($categories,$categorie['id'],$level+1);
                
                if(count($childrens)> 0)
                    $tree = array_merge($tree,$childrens);
            }
        }
        return $tree;
    }

}