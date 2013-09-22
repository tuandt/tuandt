<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 9/22/13
 */
namespace Core\Model;

class TmpDeviceTable extends AbstractModel
{
    public function getEntry($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }
    public function save(TmpDevice $entry)
    {
        $data = (array) $entry;
        $id = (int)$entry->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getEntry($id)) {
                return $this->tableGateway->update($data, array('id' => $id));
            } else {
                return $this->tableGateway->insert($data);
            }
        }
    }
    public function deleteEntry($id)
    {
        return $this->tableGateway->delete(array('id' => $id));
    }
    public function getRowsByRecyclerId($recycler_id)
    {
        $rowset = $this->tableGateway->select(array('recycler_id' => $recycler_id));
        if (!$rowset) {
            return null;
        }
        return $rowset;
    }
}