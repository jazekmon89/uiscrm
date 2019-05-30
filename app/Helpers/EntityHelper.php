<?php 

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class EntityHelper {

    const RETURN_ARR = 0;
    const RETURN_OBJ = 1;

    protected $default_types = [
        'Address', 
        'Claim', 
        'Client', 
        'Contact', 
        'ClaimType', 
        'EntityType', 
        'FileAttachment', 
        'InsurableBusiness', 
        'InsurancePolicy', 
        'Lead', 
        'InsuranceQuote', 
        'Note', 
        'Organisation', 
        'PolicyType', 
        'RFQ',  
        'Task', 
        'TaskStatus', 
        'TaskType', 
        'User', 
        'Underwriter',
        'RFQStatus',
        'PreviousClaim'
    ];

    protected $custom_types = [
        'UserContact'   => 'Contact_GetByUserID',
        'FileMetaData'  => 'FileAttachment_GetMetadata',
        'HomeAddress'   => 'Address',
        'PostalAddress' => 'Address',
        'ClientUser'    => 'GetClientUserByUserID',
        'Author'        => 'User',
    ];

    protected $match_ids = [
        'AssignToUserID'    => 'User',

        /**
        * Exception
        */
        // 'CreatedBy'         => 'Author',
    ];


    protected $blocklist = [];

	public function __construct(Model $model) {
		$this->model = $model;
	}

    public function addCustomTypes($types) {
        foreach($types as $type => $proc) {
            $this->custom_types[$type] = $proc;
        }
        return $this;
    }

    public function addMatchTypeIDs($matches) {
        foreach($matches as $ID => $type) {
            $this->match_ids[$ID] = $type;
        }
        return $this;
    }

    public function blocklist($types=null) {
        if ($types) {
            foreach($types as $type) {
                $this->blocklist[] = $type;
            }
        }
        else if ($types === false) $this->blocklist = [];
        return $this->blocklist;
    }

    public function getReturnTypeArray() {
        return self::RETURN_ARR;
    }

    public function getReturnTypeObject() {
        return self::RETURN_OBJ;
    }

	public function setModel(Model $model) {
		$this->model = $model;
	}

	public function model() {
		return $this->model;
	}

    /**
    * @return EntityType
    */
	public function getType($EntityTypeID) {
		return array_get((array)$this->get('EntityType', $EntityTypeID), 'EntityName');
	}

    /**
    * @param GUID EntityTypeID
    * @param GUID EntityID
    * 
    * @return 
    */
	public function getFromEntityTypeID($EntityTypeID, $EntityID, $depth=1, $return_type=EntityHelper::RETURN_ARR) {
		$type = $this->getType($EntityTypeID);
		$entity = $this->get($type, $EntityID, $depth, $return_type);

		if ($entity) $entity['type'] = $type;
		return $type ? $entity : [];
	}

    /**
    * @param String Entity type
    * @param GUID/UUID EntityID
    * @param Int depth 
    *
    * @return Entity | empty
    */
	public function get($type, $EntityID, $depth=1, $return_type=EntityHelper::RETURN_ARR) {

        $method = $type;
        if (!empty($this->custom_types[$type])) {
            $method = $this->custom_types[$type];
        }
		
        if (is_object($EntityID)) {
            foreach(['ID',  $type.'ID', 'EntityID'] as $field) {  
                if ($ID = array_get((array)$EntityID, $field)) 
                    break;
            }
        }
        else $ID = $EntityID;
        if (!$ID) return null;

        $entity = call_user_func_array([$this->model, $method . '_Get_first'], [$ID]);
        
        if (!$entity || $this->model->getLastSpError()) {
            return null;
        } 
        
        $entity->type = $type;
        
        if ($depth === 1) {
            return $return_type === self::RETURN_ARR ? (array)$entity : $entity;
        } 
        else {
            $depth = $depth ?: 1;
            $entity = $this->scanFields($type, $entity, $depth, $return_type);
            if ($return_type === self::RETURN_OBJ)
                $entity = (object)$entity;
        }

        return $entity;
	}


    protected function scanFields($type, $entity, $depth, $return_type) {
        $entity = (array)$entity;
        $depth = $depth > 1 ? $depth - 1 : $depth;

        foreach($entity as $field => $value) {

            /**
            * detect ID fields
            * eg: ContactID => Contact
            *     HomeAddressID => custom: HomeAddress  
            */
            $_type = substr($field, 0, strlen($field) - 2);

            if ($_type === $type || !$value) continue;

            if (in_array($_type, $this->default_types)) {
                $entity[$_type] = $this->get($_type, $value, 1, $return_type);
            }
            /**
            * For Type that didn't hava name Convention {Type}_Get
            */
            else if ($custom_type = array_get($this->custom_types, $_type)) {
                $entity[$_type] = $this->get($custom_type, $value, 1, $return_type);
            }
            /**
            * For Fields that match to default or custom types 
            * eg: AssignToUserID => User
            */
            else if ($_type = array_get($this->match_ids, $field)) {
                $entity[$_type] = $this->get($_type, $value, 1, $return_type);
            }
            else continue;
            
            if (array_has($entity, "{$_type}.{$type}ID")) {

                /**
                * remove parent to child linked ID
                * to avoid duplication of queries
                */
                array_pull($entity, "{$_type}.{$type}ID");
            }

            if (!empty($entity[$_type]) && $depth !== 1) {
                $entity[$_type] = $this->scanFields($_type, $entity[$_type], $depth, $return_type);
            }

        }
        return $entity;
    }

    /**
    * @param Entity type
    * @param Array EntityIDs
    *
    * @return Entities according to type
    *         Filtered
    */
    public function getMultiple($type, $EntityIDs, $depth=1, $return_type=EntityHelper::RETURN_ARR) {
        $entities = [];
        foreach((array)$EntityIDs as $EntityID) {
            
            if (is_object($EntityID)) {
                foreach(['ID',  $type.'ID', 'EntityID'] as $field) {  
                    if ($ID = array_get((array)$EntityID, $field)) 
                        break;
                }
            }
            else $ID = $EntityID;
            if (!$ID) continue;
            if ($entity =  $this->get($type, $ID, $depth, $return_type)) {
                $entities[] = $entity;
            }
        }
        return $entities;
    }

}