<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statisticslib extends CI_Controller
{

	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
	}

    public function payment_graph($para){

        if (!empty($para['sdate']) && !empty($para['edate'])){
            $edate = $para['edate'];
            $sdate = $para['sdate'];
        } else {
            $edate = date('Y-m-d H:i:s');
            $sdate = date('Y-m-d H:i:s', strtotime('-2 years'));
        }

        if ($para['dtype'] == 'days'){
            $date = '%Y-%m-%d';
        } else if ($para['dtype'] == 'weeks'){
            $date = '%Y-%v';
        } else if ($para['dtype'] == 'month' || empty($para['dtype'])){
            $date = '%Y-%m';
        }

        $where = ' b.creation_date between \'' . $sdate . '\' AND \'' . $edate . '\' ';

        if ($para['mem_id']) {
            $where .= ' and b.mem_id = ' . $para['mem_id'] . ' ';
        }

        $sql = "
            select
                b.a_mdate
                , sum(IFNULL(CASE b.charge_type WHEN '1' THEN amt WHEN '2' THEN amt WHEN '7' THEN amt END, 0)) AS m_spot
                , sum(IFNULL(CASE b.charge_type WHEN '5' THEN amt WHEN '6' THEN amt END, 0)) AS m_elec
                , sum(IFNULL(CASE b.charge_type WHEN '3' THEN amt END, 0)) AS m_lcl
                , sum(IFNULL(CASE b.charge_type WHEN '4' THEN amt END, 0)) AS m_acc
                , sum(IFNULL(CASE b.charge_type WHEN '1' THEN cnt WHEN '2' THEN cnt WHEN '7' THEN cnt END, 0)) AS spot
                , sum(IFNULL(CASE b.charge_type WHEN '5' THEN cnt WHEN '6' THEN cnt END, 0)) AS elec
                , sum(IFNULL(CASE b.charge_type WHEN '3' THEN cnt END, 0)) AS lcl
                , sum(IFNULL(CASE b.charge_type WHEN '4' THEN cnt END, 0)) AS acc
                , sum(b.amt) as money
            from(
                select
                    date_format(b.creation_date, '$date') as a_mdate
                  , charge_type
                  , sum(c.amount) - sum(b.price_change) as amt
                from
                    cb_orders b
                inner join
                    cb_order_details c
                    on
                    b.id = c.order_id
                where
                    $where
                    and
                    b.status in (0,1,2,3)
                    and
                    c.cal_yn = 'N'
                group by
                    date_format(b.creation_date, '$date')
                  , charge_type
            ) b
            left join (
                select
                    date_format(b.creation_date, '$date') as a_mdate
                  , charge_type as charge_type2
                  , count(*) as cnt
                from
                    cb_orders b
                where
                    $where
                    and
                    b.status in (0,1,2,3)
                group by
                    date_format(b.creation_date, '$date')
                  , charge_type
            )c on b.a_mdate = c.a_mdate and b.charge_type = c.charge_type2
            group by
                b.a_mdate
        ";
        // log_message('error', $sql);
        return $sql;
    }

    public function cycle_graph($para){

        if (!empty($para['sdate']) && !empty($para['edate'])){
            $edate = $para['edate'];
            $sdate = $para['sdate'];
        } else {
            $edate = date('Y-m-d H:i:s');
            $sdate = date('Y-m-d H:i:s', strtotime('-2 years'));
        }
        $where = ' creation_date between \'' . $sdate . '\' AND \'' . $edate . '\' ';

        if ($para['mem_id']) {
            $where .= ' and mem_id = ' . $para['mem_id'] . ' ';
        }

        $sql = "
            select
                a.cnt as ticks
                , count(*) as cnt
            from(
                select
                    phnno
                  , count(*) as cnt
                from
                    cb_orders
                where
                    $where
                    and
                    status in (0,1,2,3)
                group by
                    phnno
            ) a
            group by
                a.cnt
        ";

        return $sql;
    }

    public function amount_graph($para){

        if (!empty($para['sdate']) && !empty($para['edate'])){
            $edate = $para['edate'];
            $sdate = $para['sdate'];
        } else {
            $edate = date('Y-m-d H:i:s');
            $sdate = date('Y-m-d H:i:s', strtotime('-2 years'));
        }
        $where = ' creation_date between \'' . $sdate . '\' AND \'' . $edate . '\' ';

        if ($para['mem_id']) {
            $where .= ' and mem_id = ' . $para['mem_id'] . ' ';
        }

        $sql = "
            select
                left(c.amt/10000, 1) * 10000 as amt
                , count(*) as cnt
            from (
                select
                    a.id
                  , b.amt
                from
                    cb_orders a
                left join(
                    select
                        order_id
                      , sum(b.amount) as amt
                    from
                        cb_order_details b
                    where
                        b.cal_yn = 'N'
                    group by
                        b.order_id
                ) b on a.id = b.order_id
                where
                    $where
                    and
                    status in (0,1,2,3)
            ) c
            group by
                left(c.amt/10000, 1)
        ";

        return $sql;
    }

    public function payment_graph2($para){

        if (!empty($para['sdate']) && !empty($para['edate'])){
            $edate = $para['edate'];
            $sdate = $para['sdate'];
        } else {
            $edate = date('Y-m-d H:i:s');
            $sdate = date('Y-m-d H:i:s', strtotime('-2 years'));
        }
        $where = ' a.creation_date between \'' . $sdate . '\' AND \'' . $edate . '\' ';

        if ($para['mem_id']) {
            $where .= ' and a.mem_id = ' . $para['mem_id'] . ' ';
        }

        $sql = "
            select
            	a.a_mdate
              , ifnull(b.cnt, 0) as spot
              , ifnull(c.cnt, 0) as lcl
              , ifnull(d.cnt, 0) as acc
              , ifnull(e.cnt, 0) as elec
              , ifnull(f.money, 0) as money
              , ifnull(g.m_spot, 0) as m_spot
              , ifnull(h.m_lcl, 0) as m_lcl
              , ifnull(i.m_acc, 0) as m_acc
              , ifnull(j.m_elec, 0) as m_elec
            from (
            	select
            		date_format(creation_date, '%Y-%v') as a_mdate
            	from
            		cb_orders a
            	where
            		$where
                    and a.status in (0, 1, 2, 3)
            	group by
            		a_mdate
            ) a
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as b_mdate
            	  , count(*) as cnt
            	from
            		cb_orders a
            	where
            		$where
            		and a.status in (0, 1, 2, 3) and a.charge_type in (1,2,7)
            	group by
            		b_mdate
            ) b ON a.a_mdate = b.b_mdate
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as c_mdate
            	  , count(*) as cnt
            	from
            		cb_orders a
            	where
            		$where
            		and a.status in (0, 1, 2, 3) and a.charge_type in (3)
            	group by
            		c_mdate
            ) c ON a.a_mdate = c.c_mdate
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as d_mdate
            	  , count(*) as cnt
            	from
            		cb_orders a
            	where
            		$where
            		and a.status in (0, 1, 2, 3) and a.charge_type in (4)
            	group by
            		d_mdate
            ) d ON a.a_mdate = d.d_mdate
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as e_mdate
            	  , count(*) as cnt
            	from
            		cb_orders a
            	where
            		$where
            		and a.status in (0, 1, 2, 3) and a.charge_type in (5,6)
            	group by
            		e_mdate
            ) e ON a.a_mdate = e.e_mdate
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as b_mdate
            	  , sum(b.a_sum - a.price_change) as money
            	from
            		cb_orders a
                left join (
                	select
                	    b.order_id
                	  , sum(b.amount) as a_sum
                	from
                		cb_order_details b
                    where
                        b.cal_yn = 'N'
                	group by
                		b.order_id
                ) b on a.id = b.order_id
            	where
            		$where
            		and a.status in (0, 1, 2, 3)
            	group by
            		b_mdate
            ) f ON a.a_mdate = f.b_mdate
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as b_mdate
            	  , sum(b.a_sum - a.price_change) as m_spot
            	from
            		cb_orders a
                left join (
                	select
                	    b.order_id
                	  , sum(b.amount) as a_sum
                	from
                		cb_order_details b
                    where
                        b.cal_yn = 'N'
                	group by
                		b.order_id
                ) b on a.id = b.order_id
            	where
            		$where
            		and a.status in (0, 1, 2, 3) and a.charge_type in (1,2,7)
            	group by
            		b_mdate
            ) g ON a.a_mdate = g.b_mdate
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as b_mdate
            	  , sum(b.a_sum - a.price_change) as m_lcl
            	from
            		cb_orders a
                left join (
                	select
                	    b.order_id
                	  , sum(b.amount) as a_sum
                	from
                		cb_order_details b
                    where
                        b.cal_yn = 'N'
                	group by
                		b.order_id
                ) b on a.id = b.order_id
            	where
            		$where
            		and a.status in (0, 1, 2, 3) and a.charge_type in (3)
            	group by
            		b_mdate
            ) h ON a.a_mdate = h.b_mdate
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as b_mdate
            	  , sum(b.a_sum - a.price_change) as m_acc
            	from
            		cb_orders a
                left join (
                	select
                	    b.order_id
                	  , sum(b.amount) as a_sum
                	from
                		cb_order_details b
                    where
                        b.cal_yn = 'N'
                	group by
                		b.order_id
                ) b on a.id = b.order_id
            	where
            		$where
            		and a.status in (0, 1, 2, 3) and a.charge_type in (4)
            	group by
            		b_mdate
            ) i ON a.a_mdate = i.b_mdate
            left join(
            	select
            		date_format(creation_date, '%Y-%v') as b_mdate
            	  , sum(b.a_sum - a.price_change) as m_elec
            	from
            		cb_orders a
                left join (
                	select
                	    b.order_id
                	  , sum(b.amount) as a_sum
                	from
                		cb_order_details b
                    where
                        b.cal_yn = 'N'
                	group by
                		b.order_id
                ) b on a.id = b.order_id
            	where
            		$where
            		and a.status in (0, 1, 2, 3) and a.charge_type in (5,6)
            	group by
            		b_mdate
            ) j ON a.a_mdate = j.b_mdate
        ";
        // log_message('error', $sql);
        return $sql;
    }
}
