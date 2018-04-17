<?php
    function getDatabaseConnection() {
        $host = "us-cdbr-iron-east-05.cleardb.net";
        $username = "bf8752d16fa867";
        $password = "dc52894c";
        $dbname = "heroku_96be87d910b5efe"; 
        
        // Create connection
        $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        return $dbConn; 
    }

function getMatchingItems($query, $category, $priceFrom, $priceTo, $ordering, $showImages) {
    $db = getDatabaseConnection(); 
    
    $imgSQL = $showImages ? ', item.image_url' : ''; 
    
    $sql = "SELECT DISTINCT item.item_id, item.name, item.price $imgSQL FROM item INNER JOIN item_category ON item.item_id = item_category.item_id INNER JOIN category ON item_category.category_id =category.category_id  WHERE 1"; 

    if (!empty($query)) {
        $sql .= " AND item.name LIKE '%$query%'";
    }
    if (!empty($category)) {
        $sql .= " AND category.category_name = '$category'";
    }
    if (!empty($priceFrom)) {
        $sql .= " AND item.price >= '$priceFrom'";
    }
    if (!empty($priceTo)) {
        $sql .= " AND item.price <= '$priceTo'";
    }
    if (!empty($ordering)) {
        if ($ordering == 'product') {
            $columnName = 'item.name'; 
        } else {
            $columnName = 'item.price'; 
        }
        $sql .= " ORDER BY $columnName";
    }
    
    $statement = $db->prepare($sql); 
    
    $statement->execute(); 
    
    $items = $statement->fetchAll(); 
    
    return $items; 
}


    function getCategoriesHTML() {
        $db = getDatabaseConnection(); 
        $categoriesHTML = "<option value=''></option>";  // User can opt to not select a category 
        
        $sql = "SELECT category_name FROM category"; 
        
        $statement = $db->prepare($sql); 
        
        $statement->execute(); 
        
        $records = $statement->fetchAll(PDO::FETCH_ASSOC); 
        
        foreach ($records as $record) {
            $category = $record['category_name']; 
            $categoriesHTML .= "<option value='$category'>$category</option>"; 
        }
        
        return $categoriesHTML; 
    }
?>