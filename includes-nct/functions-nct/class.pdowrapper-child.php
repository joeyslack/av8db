<?php
class PdoWrapperChild extends PdoWrapper {
    private $_oSTH = null;
    public $sSql = '';
    public $sTable = array();
    public $aWhere = array();
    public $aColumn = array();
    public $sOther = array();
    public $aResults = array();
    public $aResult = array();
    public $iLastId = 0;
    public $iAllLastId = array();
    public $sPdoError = '';
    public $iAffectedRows = 0;
    public $aData = null;
    public $log = false;
    public $batch = false;
    const ERROR_LOG_FILE = 'PDO_Errors.log';
    const SQL_LOG_FILE = 'PDO_Sql.log';
    private $db_info = array();
    private $aValidOperation = array( 'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'SHOW');
    protected static $oPDO = null;
    public function __construct( $dsn = array() ) { parent::__construct( $dsn ); }
    public function __destruct() { $this->$oPDO = null; }
    public static function getPDO( $dsn = array() ) {
        if ( !isset( $this->$oPDO ) || ( $this->$oPDO !== null ) ) {
            $this->$oPDO = new self( $dsn );
        }
        return $this->$oPDO;
    }
    public function start() { $this->beginTransaction(); }
    public function end() { $this->commit(); }
    public function back() { $this->rollback(); }
    public function result( $iRow = 0 ) { return isset($this->aResults[$iRow]) ? $this->aResults[$iRow] : false; }
    public function affectedRows() { return is_numeric($this->iAffectedRows) ? $this->iAffectedRows : false; }
    public function getLastInsertId() { return $this->iLastId; }
    public function getAllLastInsertId() { return $this->iAllLastId; }
    public function helper() { return new PDOHelper(); }
    public function pdoQuery( $sSql = '', $aBindWhereParam = array() ) {
        $sSql         = trim( $sSql );
        $operation    = explode( ' ', $sSql );
        $operation[0] = strtoupper( $operation[0] );
        if ( !in_array( $operation[0], $this->aValidOperation ) ) {
            $this->error( 'invalid operation called in query. use only ' . implode( ', ', $this->aValidOperation ) );
        }
        if ( !empty( $sSql ) && count( $aBindWhereParam ) <= 0 ) {
            $this->sSql  = $sSql;
            $this->_oSTH = $this->prepare( $this->sSql );
            try {
                if ( $this->_oSTH->execute() ) {
                    $this->iAffectedRows = $this->_oSTH->rowCount();
                    $this->aResults      = $this->_oSTH->fetchAll();
                    $this->_oSTH->closeCursor();
                    return $this;
                } else {
                    $this->error( $this->_oSTH->errorInfo() );
                }
            }
            catch ( PDOException $e ) {
                $this->error( $e->getMessage() . ': ' . __LINE__ );
            }
        } else if ( !empty( $sSql ) && count( $aBindWhereParam ) > 0 ) {
            $this->sSql   = $sSql;
            $this->aData = $aBindWhereParam;
            $this->_oSTH  = $this->prepare( $this->sSql );
            $this->_bindPdoParam( $aBindWhereParam );
            try {
                if ( $this->_oSTH->execute() ) {
                    switch ( $operation[0] ):
                        case 'SELECT':
                            $this->iAffectedRows = $this->_oSTH->rowCount();
                            $this->aResults      = $this->_oSTH->fetchAll();
                            return $this;
                            break;
                        case 'INSERT':
                            $this->iLastId = $this->lastInsertId();
                            return $this;
                            break;
                        case 'UPDATE':
                            $this->iAffectedRows = $this->_oSTH->rowCount();
                            return $this;
                            break;
                        case 'DELETE':
                            $this->iAffectedRows = $this->_oSTH->rowCount();
                            return $this;
                            break;
                    endswitch;
                    $this->_oSTH->closeCursor();
                } else {
                    $this->error( $this->_oSTH->errorInfo() );
                }
            }
            catch ( PDOException $e ) {
                $this->error( $e->getMessage() . ': ' . __LINE__ );
            }
        } else {
            $this->error( 'Query is empty..' );
        }
    }
    public function select( $sTable = '', $aColumn = array(), $aWhere = array(), $sOther = '', $join = false ) {
        if(!is_array($aColumn))$aColumn = array();
        $sField = count( $aColumn ) > 0 ? implode( ', ', $aColumn ) : '*';
		$sTable = $join == false ? '`'.$sTable.'`' : $sTable;
        if ( !empty( $sTable ) ) {
            if ( count( $aWhere ) > 0 && is_array( $aWhere ) ) {
                $this->aData = $aWhere;
				$tmp1 = $this->customWhere($this->aData);
				$sWhere = $tmp1['where'];
                unset( $tmp );
                $this->sSql = "SELECT $sField FROM $sTable WHERE $sWhere $sOther;";
            } else {
                $this->sSql = "SELECT $sField FROM $sTable $sOther;";
            }
            $this->_oSTH = $this->prepare( $this->sSql );
            if ( count( $aWhere ) > 0 && is_array( $aWhere ) ) {
               $this->_bindPdoNameSpace( $aWhere );
            }
            try {
                if ( $this->_oSTH->execute() ) {
                    $this->iAffectedRows = $this->_oSTH->rowCount();
                    $this->aResults      = $this->_oSTH->fetchAll();
                    $this->_oSTH->closeCursor();
                    return $this;
                } else {
                    $this->error( $this->_oSTH->errorInfo() );
                }
            }
            catch ( PDOException $e ) {
                $this->error( $e->getMessage() . ': ' . __LINE__ );
            }
        }else {
            $this->error( 'Table name not found..' );
        }
    }
    public function insert( $sTable, $aData = array() ) {
        if ( !empty( $sTable ) ) {
            if ( count( $aData ) > 0 && is_array( $aData ) ) {
                foreach ( $aData as $f => $v ) {
                    $tmp[] = ":s_$f";
                }
                $sNameSpaceParam = implode( ',', $tmp );
                unset( $tmp );
                $sFields     = implode( ',', array_keys( $aData ) );
                $this->sSql  = "INSERT INTO `$sTable` ($sFields) VALUES ($sNameSpaceParam);";
                $this->_oSTH = $this->prepare( $this->sSql );
                $this->aData = $aData;
                $this->_bindPdoNameSpace( $aData );
                try {
                    if ( $this->_oSTH->execute() ) {
                        $this->iLastId = $this->lastInsertId();
                        $this->_oSTH->closeCursor();
                        return $this;
                    } else {
                        $this->error( $this->_oSTH->errorInfo() );
                    }
                }
                catch ( PDOException $e ) {
                    $this->error( $e->getMessage() . ': ' . __LINE__ );
                }
            } else {
                $this->error( 'Data not in valid format..' );
            }
        } else {
            $this->error( 'Table name not found..' );
        }
    }
    public function insertBatch( $sTable, $aData = array(), $safeModeInsert = true ) {
        $this->start();
        if ( !empty( $sTable ) ) {
            if ( count( $aData ) > 0 && is_array( $aData ) ) {
                foreach ( $aData[0] as $f => $v ) {
                    $tmp[] = ":s_$f";
                }
                $sNameSpaceParam = implode( ', ', $tmp );
                unset( $tmp );
                $sFields = implode( ', ', array_keys( $aData[0] ) );
                if ( !$safeModeInsert ) {
                    $this->sSql = "INSERT INTO `$sTable` ($sFields) VALUES ";
                    foreach ( $aData as $key => $value ) {
                        $this->sSql .= '(' . "'" . implode( "', '", array_values( $value ) ) . "'" . '), ';
                    }
                    $this->sSql  = rtrim( $this->sSql, ', ' );
                    $this->_oSTH = $this->prepare( $this->sSql );
                    try {
                        if ( $this->_oSTH->execute() ) {
                            $this->iAllLastId[] = $this->lastInsertId();
                        } else {
                            $this->error( $this->_oSTH->errorInfo() );
                        }
                    }
                    catch ( PDOException $e ) {
                        $this->error( $e->getMessage() . ': ' . __LINE__ );
                        $this->back();
                    }
                    $this->end();
                    $this->_oSTH->closeCursor();
                    return $this;
                }
                $this->sSql  = "INSERT INTO `$sTable` ($sFields) VALUES ($sNameSpaceParam);";
                $this->_oSTH = $this->prepare( $this->sSql );
                $this->aData = $aData;
                $this->batch = true;
                foreach ( $aData as $key => $value ) {
                    $this->_bindPdoNameSpace( $value );
                    try {
                        if ( $this->_oSTH->execute() ) {
                            $this->iAllLastId[] = $this->lastInsertId();
                        } else {
                            $this->error( $this->_oSTH->errorInfo() );
                            $this->back();
                        }
                    }
                    catch ( PDOException $e ) {
                        $this->error( $e->getMessage() . ': ' . __LINE__ );
                        $this->back();
                    }
                }
                $this->end();
                $this->_oSTH->closeCursor();
                return $this;
            } else {
                $this->error( 'Data not in valid format..' );
            }
        } else {
            $this->error( 'Table name not found..' );
        }
    }
    public function update( $sTable = '', $aData = array(), $aWhere = array(), $sOther = '' ) {
        if ( !empty( $sTable ) ) {
            if ( count( $aData ) > 0 && count( $aWhere ) > 0 ) {
                foreach ( $aData as $k => $v ) {
                    $tmp[] = "$k = :s_$k";
                }
                $sFields = implode( ', ', $tmp );
                unset( $tmp );
                foreach ( $aWhere as $k => $v ) {
                    $tmp[] = "$k = :s_$k";
                }
                $this->aData = $aData;
                $this->aWhere = $aWhere;
                $sWhere = implode( ' AND ', $tmp );
                unset( $tmp );
                $this->sSql  = "UPDATE `$sTable` SET $sFields WHERE $sWhere $sOther;";
                $this->_oSTH = $this->prepare( $this->sSql );
                $this->_bindPdoNameSpace( $aData );
                $this->_bindPdoNameSpace( $aWhere );
                try {
                    if ( $this->_oSTH->execute() ) {
                        $this->iAffectedRows = $this->_oSTH->rowCount();
                        $this->_oSTH->closeCursor();
                        return $this;
                    } else {
                        $this->error( $this->_oSTH->errorInfo() );
                    }
                }
                catch ( PDOException $e ) {
                    $this->error( $e->getMessage() . ': ' . __LINE__ );
                }
            } else {
                $this->error( 'update statement not in valid format..' );
            }
        } else {
            $this->error( 'Table name not found..' );
        }
    }
    public function delete( $sTable, $aWhere = array(), $sOther = '' ) {
        if ( !empty( $sTable ) ) {
            if ( count( $aWhere ) > 0 && is_array( $aWhere ) ) {
                foreach ( $aWhere as $k => $v ) {
                    $tmp[] = "$k = :s_$k";
                }
                $sWhere = implode( ' AND ', $tmp );
                unset( $tmp );
                $this->sSql  = "DELETE FROM `$sTable` WHERE $sWhere $sOther;";
                $this->_oSTH = $this->prepare( $this->sSql );
                $this->_bindPdoNameSpace( $aWhere );
                $this->aData = $aWhere;
                try {
                    if ( $this->_oSTH->execute() ) {
                        $this->iAffectedRows = $this->_oSTH->rowCount();
                        $this->_oSTH->closeCursor();
                        return $this;
                    } else {
                        $this->error( $this->_oSTH->errorInfo() );
                    }
                }
                catch ( PDOException $e ) {
                    $this->error( $e->getMessage() . ': ' . __LINE__ );
                }
            } else {
                $this->error( 'Not a valid where condition..' );
            }
        } else {
            $this->error( 'Table name not found..' );
        }
    }
    public function results( $type = 'array' ) {
        switch ( $type ) {
            case 'array':
                return $this->aResults;
                break;
            case 'xml':
                header( "Content-Type:text/xml" );
                return $this->helper()->arrayToXml( $this->aResults );
                break;
            case 'json':
                header( 'Content-type: application/json; charset="utf-8"' );
                return json_encode( $this->aResults );
                break;
        }
    }
    public function count( $sTable = '', $aWhere = array(), $sOther = '' , $join = false) {
        if ( !empty( $sTable ) ) {
			$sTable = $join == false ? '`'.$sTable.'`' : $sTable;
            if ( count( $aWhere ) > 0 && is_array( $aWhere ) ) {
                $this->aData = $aWhere;
				$tmp1 = $this->customWhere($this->aData);
				$sWhere = $tmp1['where'];
                unset( $tmp );
                $this->sSql = "SELECT COUNT(*) AS NUMROWS FROM $sTable WHERE $sWhere $sOther;";
            }else{
                $this->sSql = "SELECT COUNT(*) AS NUMROWS FROM $sTable $sOther;";
            }
            $this->_oSTH = $this->prepare( $this->sSql );
            if ( count( $aWhere ) > 0 && is_array( $aWhere ) ) {
               $this->_bindPdoNameSpace( $aWhere );
            }
            try {
                if ( $this->_oSTH->execute() ) {
                    $this->aResults = $this->_oSTH->fetch();
                    $this->_oSTH->closeCursor();
                    return $this->aResults['NUMROWS'];
                } else {
                    $this->error( $this->_oSTH->errorInfo() );
                }
            }
            catch ( PDOException $e ) {
                $this->error( $e->getMessage() . ': ' . __LINE__ );
            }
        } else {
            $this->error( 'Table name not found..' );
        }
    }
    public function truncate($sTable =''){
        if ( !empty( $sTable ) ) {
            $this->sSql  = "TRUNCATE TABLE `$sTable`;";
            $this->_oSTH = $this->prepare( $this->sSql );
            try {
                if ( $this->_oSTH->execute() ) {
                    $this->_oSTH->closeCursor();
                    return true;
                } else {
                    $this->error( $this->_oSTH->errorInfo() );
                }
            }
            catch ( PDOException $e ) {
                $this->error( $e->getMessage() . ': ' . __LINE__ );
            }
        } else {
            $this->error( 'Table name not found..' );
        }
    }
    public function drop($sTable =''){
        if ( !empty( $sTable ) ) {
            $this->sSql  = "DROP TABLE `$sTable`;";
            $this->_oSTH = $this->prepare( $this->sSql );
            try {
                if ( $this->_oSTH->execute() ) {
                    $this->_oSTH->closeCursor();
                    return true;
                } else {
                    $this->error( $this->_oSTH->errorInfo() );
                }
            }
            catch ( PDOException $e ) {
                $this->error( $e->getMessage() . ': ' . __LINE__ );
            }
        } else {
            $this->error( 'Table name not found..' );
        }
    }
    public function describe( $sTable = '' ) {
        $this->sSql = $sSql  = "DESC $sTable;";
        $this->_oSTH = $this->prepare( $sSql );
        $this->_oSTH->execute();
        $aColList = $this->_oSTH->fetchAll();
        foreach ( $aColList as $key ) {
            $aField[] = $key['Field'];
            $aType[]  = $key['Type'];
        }
        return array_combine( $aField, $aType );
    }
    public function customWhere ($array_data = array()){
        $syntax = '';
		$tmp = array();
		$i = 0;
        foreach ($array_data as $key => $value) {
            $key = trim($key);
            if(strstr($key, ' ')){
                $array = explode(' ',$key);
                if(count($array)=='2'){
                    $random = '';//"_".rand(1,100);
                    $field = $array[0];
                    $operator  = $array[1];
                    $tmp[] = "$field $operator :s_$field"."$random";
                    $syntax .= " $field $operator :s_$field"."$random ";
                }elseif(count($array)=='3'){
                    $random = '';//"_".rand(1,100);
                    $condition = $array[0];
                    $field = $array[1];
                    $operator = $array[2];
                    $tmp[] = "$condition $field $operator :s_$field"."$random";
                    $syntax .= " $condition $field $operator :s_$field"."$random ";
                }
            }else{
				$syntax .= ($i > 0 ? ' AND ' : '')." $key = :s_$key";
				$tmp[] = ($i > 0 ? ' AND ' : '')." $key = :s_$key";
			}
			$i++;
        }
        return array(
            'where' => $syntax,
            'bind' => implode(' ',$tmp)
        );
    }
    private function _bindPdoNameSpace( $array = array() ) {
		foreach ( $array as $f => $v ) {
	        if(strstr($f, ' ')){
                $field = $this->getFieldFromArrayKey($f);
                switch ( gettype( $array[$f] ) ):
                    case 'string':
                        $this->_oSTH->bindParam( ":s" . "_" . "$field", $array[$f], PDO::PARAM_STR );
                        break;
                    case 'integer':
                        $this->_oSTH->bindParam( ":s" . "_" . "$field", $array[$f], PDO::PARAM_INT );
                        break;
                    case 'boolean':
                        $this->_oSTH->bindParam( ":s" . "_" . "$field", $array[$f], PDO::PARAM_BOOL );
                        break;
                    case 'float':
                        $this->_oSTH->bindParam( ":s" . "_" . "$field", $array[$f], PDO::PARAM_FLOAT );
                        break;	
					case 'NULL':
                        $this->_oSTH->bindValue(":s" . "_" . "$field", $array[$f], PDO::PARAM_NULL);
                        break;	
                endswitch;
	        }else{
            switch ( gettype( $array[$f] ) ):
                case 'string':
                    $this->_oSTH->bindParam( ":s" . "_" . "$f", $array[$f], PDO::PARAM_STR );
                    break;
                case 'integer':
                    $this->_oSTH->bindParam( ":s" . "_" . "$f", $array[$f], PDO::PARAM_INT );
                    break;
                case 'boolean':
                    $this->_oSTH->bindParam( ":s" . "_" . "$f", $array[$f], PDO::PARAM_BOOL );
                    break;
            endswitch;
        }
        }
    }
    private function _bindPdoParam( $array = array() ) {
        foreach ( $array as $f => $v ) {
            switch ( gettype( $array[$f] ) ):
                case 'string':
                    $this->_oSTH->bindParam( $f + 1, $array[$f], PDO::PARAM_STR );
                    break;
                case 'integer':
                    $this->_oSTH->bindParam( $f + 1, $array[$f], PDO::PARAM_INT );
                    break;
                case 'boolean':
                    $this->_oSTH->bindParam( $f + 1, $array[$f], PDO::PARAM_BOOL );
                    break;
            endswitch;
        }
    }
    public function error( $msg ) {
        file_put_contents( $this->ERROR_LOG_FILE, date( 'Y-m-d h:m:s' ) . ' :: ' . $msg . "\n", FILE_APPEND );
        return false;
    }
    public function showQuery($logfile=false) {
        if(!$logfile){
            echo "<div style='color:#990099; border:1px solid #777; padding:2px; background-color: #E5E5E5;'>";
            echo " Executed Query -> <span style='color:#008000;'> ";
            echo $this->helper()->formatSQL( $this->interpolateQuery() );
            echo "</span></div>";
            return $this;
        }else{
            file_put_contents( $this->SQL_LOG_FILE, date( 'Y-m-d h:m:s' ) . ' :: ' . $this->interpolateQuery() . "\n", FILE_APPEND );
            return $this;
        }
    }
    protected function interpolateQuery() {
       $sql = $this->_oSTH->queryString;
       if(!$this->batch){
        $params = ( ( is_array( $this->aData ) ) && ( count( $this->aData ) > 0 ) ) ? $this->aData : $this->sSql;
        if ( is_array( $params ) ) {
            foreach ( $params as $key => $value ) {
                if(strstr($key, ' ')){
                    $real_key = $this->getFieldFromArrayKey($key);
                    $params[$key] = is_string( $value ) ? '"' . $value . '"' : $value;
                    $keys[]       = is_string( $real_key ) ? '/:s_' . $real_key . '/' : '/[?]/';
                }else{
                    $params[$key] = is_string( $value ) ? '"' . $value . '"' : $value;
                    $keys[]       = is_string( $key ) ? '/:s_' . $key . '/' : '/[?]/';
                }
            }
            $sql = preg_replace( $keys, $params, $sql, 1, $count );
            if(strstr($sql,':s_')){
                foreach ( $this->aWhere as $key => $value ) {
                    if(strstr($key, ' ')){
                        $real_key = $this->getFieldFromArrayKey($key);
                        $params[$key] = is_string( $value ) ? '"' . $value . '"' : $value;
                        $keys[]       = is_string( $real_key ) ? '/:s_' . $real_key . '/' : '/[?]/';
                    }else{
                        $params[$key] = is_string( $value ) ? '"' . $value . '"' : $value;
                        $keys[]       = is_string( $key ) ? '/:s_' . $key . '/' : '/[?]/';
                    }
                }
                $sql = preg_replace( $keys, $params, $sql, 1, $count );
            }
            return $sql;
        } else {
            return $params;
        }
       }else{
           $params_batch = ( ( is_array( $this->aData ) ) && ( count( $this->aData ) > 0 ) ) ? $this->aData : $this->sSql;
           $batch_query = '';
           if ( is_array( $params_batch ) ) {
               foreach ($params_batch as $keys => $params){
                   echo $params;
                   foreach ( $params as $key => $value ) {
                       if(strstr($key, ' ')){
                           $real_key = $this->getFieldFromArrayKey($key);
                           $params[$key] = is_string( $value ) ? '"' . $value . '"' : $value;
                           $array_keys[]       = is_string( $real_key ) ? '/:s_' . $real_key . '/' : '/[?]/';
                       }else{
                           $params[$key] = is_string( $value ) ? '"' . $value . '"' : $value;
                           $array_keys[]       = is_string( $key ) ? '/:s_' . $key . '/' : '/[?]/';
                       }
                   }
                   $batch_query .= "<br />".preg_replace( $array_keys, $params, $sql, 1, $count );
               }
               return $batch_query;
           } else {
               return $params_batch;
           }
       }
    }
    public function getFieldFromArrayKey($array_key=array()){
        $key_array = explode(' ',$array_key);
        return (count($key_array)=='2') ? $key_array[0] : ((count($key_array)> 2) ? $key_array[1] : $key_array[0]);
    }
    public function setErrorLog( $mode = false ) { $this->log = $mode; }
}