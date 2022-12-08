<?php

class Utente{
    protected static $db;
    public $id;
    public $utente;
    public $ora;
    public $estado;

    public function __construct($arr=[]){
        $this->id =$arr['id'] ?? '';
        $this->utente =$arr['utente'] ?? '';
        $this->ora = time();
        $this->estado =$arr['estado'] ?? 0;
    }
    //collegamento a database
    public static function setDB($database){
        self::$db = $database;
    }
    //Cerca l'ultimo registro per un'utente specifico. Ritorna un'Obj(Utente) con i dati se l'utente è trovato. Atrimenti ritorna NULL
    public static function lastInsertUser($utente){
        var_dump($utente=self::$db->real_escape_string($utente));
        $query = "SELECT * FROM traccia  where utente = '${utente}' ORDER BY ora DESC LIMIT 1";
        $risultato = self::consultarSQL($query);

        return array_shift($risultato);
        
    }

    //Inserisce nella DB, un registro dell'utente, con il tempo attuale, 
    public function insertUserLog() {
        $this->ora = date("Y-m-d H:i:s");
        $query = "INSERT INTO traccia (utente, ora, estado) VALUES ( '" . $this->utente . "' , '" . $this->ora . "' , '" . $this->estado . "' )"; 
        $risultato = self::$db->query($query);      
        return $risultato;  
    }
    //crea l'instancia dell'oggetto(Utente), tomando un array asociativo como argumento
    protected static function crearObjeto($registro){
        $objeto=new static;
        foreach($registro as $key=>$value){
            if(property_exists($objeto, $key)){
                $objeto->$key = $value;
            }
        }
        return $objeto;

    }

    public static function consultarSQL($query){
        //consulta la query en DB
        $resultado = self::$db->query($query);

        //itera resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()){
            $array[]= self::crearObjeto($registro);
        }
        //libera memoria
        $resultado->free();
        
        //retorna resultados
        return $array;
    }
  
    public static function consultarRegistros ($utente, $inizio, $fine){
        var_dump($inizio);
        var_dump($utente=self::$db->real_escape_string($utente));
        var_dump($inizio=self::$db->real_escape_string($inizio));
        var_dump($fine=self::$db->real_escape_string($fine));
        if ($utente=='') {
            $query = "SELECT * FROM traccia WHERE ora BETWEEN '${inizio} 00:00:00' AND '${fine} 23:59:59' ORDER BY utente";
        } else {
            $query = "SELECT * FROM traccia WHERE ora BETWEEN '${inizio} 00:00:00' AND '${fine} 23:59:59' AND utente = '${utente}'";
        }
       
        $registros = self::consultarSQL($query);
        return $registros;

    }

}