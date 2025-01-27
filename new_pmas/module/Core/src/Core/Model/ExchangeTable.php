<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 9/20/13
 */
namespace Core\Model;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Debug\Debug;

class ExchangeTable extends AbstractModel
{
    /**
     * Get row by country id
     * @param $id
     * @return array|\ArrayObject|null
     */
    public function getRowByCountry($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('country_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }
    public function save(Exchange $entry)
    {
        $data = (array) $entry;
        if($this->checkExistExchange($data) == false){
            return $this->tableGateway->insert($data);
        }else{
            return 0;
        }
    }
    public function checkExistExchange($data)
    {
        if(!empty($data)){
            $rowset = $this->tableGateway->select(array(
                'currency' => $data['currency'],
                'exchange_rate' => $data['exchange_rate'],
                'time' => $data['time'],
            ));
            $row = $rowset->current();
            if(!$row){
                return false;
            }
            return true;
        }
    }
    /**
     * @return null|\Zend\Db\ResultSet\ResultSet
     */
    public function getLastExchange()
    {
        $rowset = $this->tableGateway->select(function (Select $select){
            $select->order('time DESC')->limit(5);
        });
        if($rowset->count() <= 0){
            return null;
        }
        return $rowset;
    }

    /**
     * @param $currency
     */
    public function getLastCurrencyExchange($currency)
    {
        if(!$currency){
            return null;
        }
        $adapter = $this->tableGateway->adapter;
        $sql = new Sql($adapter);
        $select = $sql->select()->from(array('m' => $this->tableGateway->table));
        $select->where(array('currency' => $currency));
        $select->order('m.time DESC');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        if($result->count() <= 0){
            return null;
        }
        $row = $result->current();
        if(!empty($row)){
            return $row;
        }
        return null;
    }
    /**
     * @return array|null
     */
    public function getAvailableCurrencies()
    {
        $rowset = $this->tableGateway->select();
        if($rowset->count() <= 0){
            return null;
        }
        $data = array();
        foreach($rowset as $row){
            $data[$row->currency] = $row->currency;
        }
        return $data;
    }

    /**
     * @param $currency
     * @param null $start
     * @param null $end
     * @return null
     */
    public function getRowsetByCurrency($currency = null,$start = null,$end = null)
    {
        $adapter = $this->tableGateway->adapter;
        $sql = new Sql($adapter);
        $select = $sql->select()->from(array('m' => $this->tableGateway->table));
        if($currency != null){
            if($start == null && $end == null){
                $select->where('m.currency = \''.$currency.'\'');
            }elseif($start != null && $end == null){
                $select->where('m.currency = \''.$currency.'\' AND m.time >= '.$start);
            }elseif($start == null && $end != null){
                $select->where('m.currency = \''.$currency.'\' AND m.time <= '.$end);
            }elseif($start != null && $end != null){
                $select->where('m.currency = \''.$currency.'\' AND m.time >= '.$start.' AND m.time <= '.$end);
            }
        }
        $selectString = $sql->getSqlStringForSqlObject($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        if($result->count() <= 0){
            return null;
        }
        return $result;
    }

    /**
     * @param null $currency
     * @param null $start
     * @param null $end
     * @return null
     */
    public function getExchangeByCurrency($currency = null,$start = null,$end = null)
    {
        if($currency == null){
            return null;
        }
        $adapter = $this->tableGateway->adapter;
        $sql = new Sql($adapter);
        $select = $sql->select()->from(array('m' => $this->tableGateway->table));
        if($currency != null){
            if($start == null && $end == null){
                $select->where('m.currency = \''.$currency.'\'');
            }elseif($start != null && $end == null){
                $select->where('m.currency = \''.$currency.'\' AND m.time >= '.$start);
            }elseif($start == null && $end != null){
                $select->where('m.currency = \''.$currency.'\' AND m.time <= '.$end);
            }elseif($start != null && $end != null){
                $select->where('m.currency = \''.$currency.'\' AND m.time >= '.$start.' AND m.time <= '.$end);
            }
        }
        $select->order('exchange_rate DESC');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        if($result->count() <= 0){
            return null;
        }
        return $result;
    }
    public function getHighestExchangeByCurrency($currency = null,$start = null,$end = null)
    {
        if($currency == null){
            return null;
        }
        $adapter = $this->tableGateway->adapter;
        $sql = new Sql($adapter);
        $select = $sql->select()->from(array('m' => $this->tableGateway->table));
        if($currency != null){
            if($start == null && $end == null){
                $select->where('m.currency = \''.$currency.'\'');
            }elseif($start != null && $end != null){
                $select->where('m.currency = \''.$currency.'\' AND m.time BETWEEN '.$start.' AND '.$end);
            }elseif($start != null && $end == null){
                $select->where('m.currency = \''.$currency.'\' AND m.time >= '.$start);
            }elseif($start == null && $end != null){
                $select->where('m.currency = \''.$currency.'\' AND m.time <= '.$end);
            }
        }
        $select->order('exchange_rate DESC');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        if($result->count() <= 0){
            return null;
        }
        $row = $result->current();
        if(empty($row)){
            return null;
        }
        return $row;
    }
    public function getCurrentExchangeOfCurrency($currency = null,$time = null)
    {
        if($currency == null){
            return null;
        }
        $adapter = $this->tableGateway->adapter;
        $sql = new Sql($adapter);
        $select = $sql->select()->from(array('m' => $this->tableGateway->table));
        if($currency != null){
            if($time == null){
                $select->where('m.currency = \''.$currency.'\' AND m.time <= '.time());
            }else{
                $select->where('m.currency = \''.$currency.'\' AND m.time <= '.$time);
            }
        }
        $select->order('m.time DESC')->limit(1);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        if($result->count() <= 0){
            return null;
        }
        $row = $result->current();
        if(empty($row)){
            return null;
        }
        return $row;
    }
}