<?php

class DbProcess extends Zend_Db_Table_Abstract{
    protected $_select ;
    
    /**
     * @params sequence defalut is 1, means the table has auto_increment field
    */
    public function insertTab( $tableName, $aData, $sequence = 1){
        if ($sequence == 1 || !isset($sequence)) {
            $this->_db->insert( $tableName, $aData );
            return $this->_db->lastInsertId( $tableName );  
        } else {
            return $this->_db->insert($tableName, $aData);
        }
    }
    
    public function updateTab( $tableName, $aData , $aWhere ){
    	return $this->_db->update( $tableName, $aData , $this->analyseWhere($aWhere)) ;   
    }
	public function queryTab( $sql ){
    	return $this->_db->query( $sql) ;   
    }
    public function deleteTab( $tableName, $aWhere ){
        return $this->_db->delete( $tableName, $this->analyseWhere($aWhere)) ;   
    }
    
    public function getList( $tableName , $fields = '*', $aWhere = NULL , $nStart = NULL , $nLimit = NULL , $strSort = NULL , $strDir = NULL,$strGroup=NULL){        
    	$this->_select = $this->_db->select();
    	$this->_select->limit ( $nLimit , $nStart ) ;
    	$this->_select->from( $tableName, $fields );

    	if(!empty($aWhere))
			$this->_select->where( $this->analyseWhere($aWhere) );

		if(!empty($strSort) && !empty($strDir))
    	    $this->_select->order( $strSort.' '. $strDir ) ;
		if(!empty($strGroup))
    	    $this->_select->group( $strGroup ) ;

        $sql = $this->_select->__toString() ;
        $result = $this->_db->fetchAll( $sql ) ;
        return $result ; 
    }
    
    public function getListAll( $tableName, $fields = "*", $aWhere = Array()){
    	if( !isset( $aWhere ) || empty( $aWhere ) )
            $strLocator = '1=1' ;
        else
        	$strLocator = $this->analyseWhere($aWhere) ;
        
        $this->_select = $this->_db->select();
        $this->_select->from( $tableName, $fields )
                      ->where( $strLocator ) ;
        $sql = $this->_select->__toString() ;
        $result = $this->_db->fetchAll( $sql ) ;
        return $result ;    
    }
    
    public function getListOne($tableName, $fields = "*", $aWhere = Array(), $strSort = NULL , $strDir = NULL, $strGroup=NULL){
        if( !isset( $aWhere ) || empty( $aWhere ) )
            $strLocator = '1=1' ;
        else
            $strLocator = $this->analyseWhere($aWhere) ;
        $this->_select = $this->_db->select();
        $this->_select->from( $tableName, $fields )
                      ->where( $strLocator ) ;

        if(!empty($strSort) && !empty($strDir))
            $this->_select->order( $strSort.' '. $strDir ) ;
        if(!empty($strGroup))
            $this->_select->group( $strGroup ) ;

        $sql = $this->_select->__toString() ;
        $result = $this->_db->fetchRow( $sql ) ;
        return $result ;    
    }

    public function getCount( $tableName, $aWhere = NULL ){
        if( !isset( $aWhere ) || empty( $aWhere ) )
            $strLocator = '1=1' ;
        else
        	$strLocator = $this->analyseWhere($aWhere) ;

        $this->_select = $this->_db->select();
    	$this->_select->from( $tableName, 'count(*) as count' )
			          ->where( $strLocator ) ;
        $sql = $this->_select->__toString() ;
        $total = $this->_db->fetchCol( $sql ) ;
        return $total[0] ;    
    }
    
    /**
     * [analyseWhere description]
     * @param  [type] $aWhere is array , array("locat_app_id" => 1, "notequal_email" => "test@rising.com.cn")
     * @return [type] $strWhere is string, " app_id=1 and email!=test@rising.com.cn"
     */
    public function analyseWhere( $aWhere ){
    	$strLocator = $strTempLocator = "";
        if( is_array( $aWhere ) )
        {
            $nCount = 0 ;
            foreach( $aWhere as $key => $val )
            {
                $aTemp = explode( '_' , $key , 2 ) ;
                switch( $aTemp[0] )
                {
                    case 'locate' :
                        if( !empty( $val ) )
                        {
                            $strTempLocator = $this->getAdapter()->quoteIdentifier( $aTemp[1] ) . ' = ' . $this->getAdapter()->quote( $val ) ;
                        }
                        else
                        {
                            $strTempLocator =  $aTemp[1]. ' is NULL' ;    
                        }
                        break ;
                    case 'inrange'    :
                    case 'notinrange' :
                        switch( $aTemp[0] )
                        {
                            case 'inrange' :
                                $strOperator = " IN " ;
                                break ;
                            case 'notinrange' :
                                $strOperator = " NOT IN " ;
                                break ;
                        }
                        $nCountValue = 0 ;
                        $strQuoteValue = "";
                        foreach( $val as $k => $v )
                        {
                            if( $nCountValue > 0 )
                            {
                                $strQuoteValue .= ' , "' . $v .'"';
                            }
                            else
                            {
                                $strQuoteValue .= '"'.$v.'"' ;    
                            }
                            $nCountValue++ ;
                        }
                        $strTempLocator = $aTemp[1] . $strOperator . '( ' . trim($strQuoteValue,',')  . ' ) ' ;
                        break ;
                    case 'search' :
                        $strTempLocator = $aTemp[1] . ' LIKE \'%' . $val . '%\' ' ;
                        break ;
                    case 'largethan' :
                    case 'largeequal':
                    case 'lessthan'  :
                    case 'lessequal' :
                    case 'notequal'  :
                        switch( $aTemp[0] )    
                        {
                            case "largethan":
								$strOperator = " > ";
								break;
							case "largeequal":
								$strOperator = " >= ";
								break;
							case "lessthan":
								$strOperator = " < ";
								break;
							case "lessequal":
								$strOperator = " <= ";
								break;
							case "notequal":
							    $strOperator = " != ";
								break;
                        }
                        $strTempLocator = $aTemp[1] . $strOperator .  $this->getAdapter()->quote( $val );
                        break ;
                    case 'between' :
                        $strTempLocator = $aTemp[1]  . ' BETWEEN ' . $val[0]. ' AND ' . $val[1];
                        break ;
                    default :
                        break ;   
                }
                if( $nCount > 0 )
                {
                    $strLocator .= ' AND ' . $strTempLocator ;    
                }
                else
                {
                    $strLocator = $strTempLocator ;       
                }
                $nCount++ ;
            }
        }   
        else
        {
            $strLocator = $aLocator ;    
        } 
        return $strLocator ;
    }
    
    /**
     * [analyseWhere description]
     * @param  [type] $aWhere is array , array("locat_app_id" => 1, "notequal_email" => "test@rising.com.cn")
     * @return [type] $strWhere is string, " app_id=1 and email!=test@rising.com.cn"
     */
    public function analyseWhereDw( $aWhere ){
    	$strLocator = $strTempLocator = "";
        if( is_array( $aWhere ) )
        {
            $nCount = 0 ;
            foreach( $aWhere as $key => $val )
            {
                $aTemp = explode( '_' , $key , 3 ) ;
                switch( $aTemp[0] )
                {
                    case 'locate' :
                        if( !empty( $val ) )
                        {
                            $strTempLocator = $this->getAdapter()->quoteIdentifier( $aTemp[2] ) . ' = ' . $this->getAdapter()->quote( $val ) ;
                        }
                        else
                        {
                            $strTempLocator =  $aTemp[2]. ' is NULL' ;    
                        }
                        break ;
                    case 'inrange'    :
                    case 'notinrange' :
                        switch( $aTemp[0] )
                        {
                            case 'inrange' :
                                $strOperator = " IN " ;
                                break ;
                            case 'notinrange' :
                                $strOperator = " NOT IN " ;
                                break ;
                        }
                        $nCountValue = 0 ;
                        $strQuoteValue = "";
                        foreach( $val as $k => $v )
                        {
                            if( $nCountValue > 0 )
                            {
                                $strQuoteValue .= ' , "' . $v .'"';
                            }
                            else
                            {
                                $strQuoteValue .= '"'.$v.'"' ;    
                            }
                            $nCountValue++ ;
                        }
                        $strTempLocator = $aTemp[2] . $strOperator . '( ' . trim($strQuoteValue,',')  . ' ) ' ;
                        break ;
                    case 'search' :
                        $strTempLocator = $aTemp[2] . ' LIKE \'%' . $val . '%\' ' ;
                        break ;
                    case 'largethan' :
                    case 'largeequal':
                    case 'lessthan'  :
                    case 'lessequal' :
                    case 'notequal'  :
                        switch( $aTemp[0] )    
                        {
                            case "largethan":
								$strOperator = " > ";
								break;
							case "largeequal":
								$strOperator = " >= ";
								break;
							case "lessthan":
								$strOperator = " < ";
								break;
							case "lessequal":
								$strOperator = " <= ";
								break;
							case "notequal":
							    $strOperator = " != ";
								break;
                        }
                        $strTempLocator = $aTemp[2] . $strOperator .  $this->getAdapter()->quote( $val );
                        break ;
                    case 'between' :
                        $strTempLocator = $aTemp[2]  . ' BETWEEN ' . $val[0]. ' AND ' . $val[1];
                        break ;
                    default :
                        break ;   
                }
                //var_dump($nCount > 0,$aTemp,$strTempLocator);
                if( $nCount > 0 )
                {
                    $strLocator .= $aTemp[1] .' '. $strTempLocator ;    
                }
                else
                {
                    $strLocator = $strTempLocator ;       
                }
                $nCount++ ;
            }
        }   
        else
        {
            $strLocator = $aLocator ;    
        } 
        return $strLocator ;
    }
}
