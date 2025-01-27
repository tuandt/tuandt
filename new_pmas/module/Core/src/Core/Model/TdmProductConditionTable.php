<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 9/18/13
 */
namespace Core\Model;

class TdmProductConditionTable extends AbstractModel
{
    public function getEntry($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('condition_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }
    public function save(TdmProductCondition $entry)
    {
        $data = (array) $entry;
        $id = (int)$entry->condition_id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getEntry($id)) {
                return $this->tableGateway->update($data, array('condition_id' => $id));
            } else {
                throw new \Exception('Data does not exist.');
            }
        }
    }

    /**
     * Delete
     * @param $id
     * @return int
     */
    public function deleteEntry($id)
    {
        return $this->tableGateway->delete(array('condition_id' => $id));
    }

    /**
     * Clear data which has condition
     * @param $id
     * @return bool
     */
    public function clearTdmCondition($id)
    {
        if($this->deleteEntry($id)){
            $success = true;
            $tdmProductTable = $this->serviceLocator->get('TdmProductTable');
            if($tdmProductTable->checkHasRowContainConditionId($id)){
                $success = $success && $tdmProductTable->deleteByCondition($id);
            }
            return $success;
        }else{
            return false;
        }
    }
    /**
     * @param $name
     * @return array|\ArrayObject|null
     */
    public function getEntryByName($name)
    {
        $name  = (string) $name;
        $rowset = $this->tableGateway->select(array('name' => $name));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }
}