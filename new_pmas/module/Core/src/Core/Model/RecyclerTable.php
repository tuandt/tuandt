<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 9/17/13
 */
namespace Core\Model;

use Zend\Db\Sql\Select;
use Zend\Debug\Debug;

class RecyclerTable extends AbstractModel
{
    public function getAvaiableRows()
    {
        $rowset = $this->tableGateway->select(array('deleted' => 0));
        if (!$rowset) {
            return null;
        }
        return $rowset;
    }
    public function getEntry($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('recycler_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }
    public function save(Recycler $entry)
    {
        $data = (array) $entry;
        $id = (int)$entry->recycler_id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getEntry($id)) {
                return $this->tableGateway->update($data, array('recycler_id' => $id));
            } else {
                throw new \Exception('Data does not exist.');
            }
        }
    }
    public function deleteEntry($id)
    {
        return $this->tableGateway->update(array('deleted' => 1),array('recycler_id' => $id));
    }

    /**
     * @param $id
     * @return bool
     */
    public function clearRecycler($id)
    {
        if($this->deleteEntry($id)){
            $success = true;
            $recyclerProductTable = $this->serviceLocator->get('RecyclerProductTable');
            if($recyclerProductTable->checkHasRowHasRecyclerId($id)){
                $success = $success && $recyclerProductTable->deleteByRecyclerId($id);
            }
            return $success;
        }else{
            return false;
        }
    }
    /**
     * Delete by country id
     * @param $country_id
     * @return int
     */
    public function clearCountry($country_id)
    {
        return $this->tableGateway->update(array('country_id' => 0),array('country_id' => $country_id));
    }
    /**
     * check if has record contain country id
     * @param $country_id
     * @return bool
     */
    public function checkAvailabelCountry($country_id)
    {
        $rowset = $this->tableGateway->select(array('country_id' => $country_id,'deleted' => 0));
        if ($rowset->count() <= 0) {
            return false;
        }
        return true;
    }
    /**
     * Get available recyclers
     * @param null $ids
     * @return null|\Zend\Db\ResultSet\ResultSet
     */
    public function getAvailabeRecyclers($ids = null)
    {
        if($ids == null){
            $rowset = $this->tableGateway->select(array('deleted' => 0));
        }else{
            $rowset = $this->tableGateway->select(array('deleted' => 0,'recycler_id' => $ids));
        }
        if (!$rowset) {
            return null;
        }
        return $rowset;
    }

    /**
     * @return null|\Zend\Db\ResultSet\ResultSet
     */
    public function getLastRecyclers()
    {
        $rowset = $this->tableGateway->select(function (Select $select){
            $select->where('deleted = 0');
            $select->order('recycler_id DESC')->limit(10);
        });
        if($rowset->count() <= 0){
            return null;
        }
        return $rowset;
    }

    /**
     * @param $country_id
     * @return null|\Zend\Db\ResultSet\ResultSet
     */
    public function getRecyclersByCountry($country_id)
    {
        $country_id = (int) $country_id;
        $rowset = $this->tableGateway->select(array('deleted' => 0,'country_id' => $country_id));
        if (!$rowset) {
            return null;
        }
        return $rowset;
    }
}