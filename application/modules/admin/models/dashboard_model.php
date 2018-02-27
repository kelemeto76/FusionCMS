<?php

class Dashboard_model extends CI_Model
{
	public function getUnique($type)
	{
		if($type == "today")
		{
			$date = date("Y-m-d");
		}
		else
		{
			$date = date("Y-m-d", time() - 60*60*24*30);
		}

		if($type == "today")
		{
			$query = $this->db->query("SELECT COUNT(DISTINCT ip,`date`) as `total` FROM visitor_log WHERE `date` >= ?", array($date));
		}
		else
		{
			$query = $this->db->query("SELECT COUNT(DISTINCT ip) as `total` FROM visitor_log WHERE `date` >= ?", array($date));
		}
		
		$row = $query->result_array();
		
		return $row[0]['total'];
	}

	public function getViews($type)
	{
		if($type == "today")
		{
			$date = date("Y-m-d");
		}
		else
		{
			$date = date("Y-m-d", time() - 60*60*24*30);
		}

		$query = $this->db->query("SELECT COUNT(*) as `total` FROM visitor_log WHERE `date` >= ?", array($date));

		$row = $query->result_array();
		
		return $row[0]['total'];
	}

	public function getIncome($type)
	{
		if($type == "this")
		{
			$date = date("Y-m");
		}
		else
		{
			$date = date("Y-m", time() - 60*60*24*30);
		}

		$query = $this->db->query("SELECT amount FROM monthly_income WHERE month=?", array($date));

		if($query->num_rows())
		{
			$row = $query->result_array();

			return $row[0]['amount'];
		}
		else
		{
			return 0;
		}
	}

	public function getVotes($type)
	{
		if($type == "this")
		{
			$date = date("Y-m");
		}
		else
		{
			$date = date("Y-m", time() - 60*60*24*30);
		}

		$query = $this->db->query("SELECT amount FROM monthly_votes WHERE month=?", array($date));

		if($query->num_rows())
		{
			$row = $query->result_array();

			return $row[0]['amount'];
		}
		else
		{
			return 0;
		}
	}

	public function getSignupsMonthly($type)
	{
		if($type == "this")
		{
			$date = date("Y-m")."-0";
		}
		else
		{
			$date = date("Y-m", time() - 60*60*24*30)."-0";
			$next = date("Y-m")."-0";
		}

		if($type == "this")
		{
			$query = $this->db->query("SELECT amount FROM daily_signups WHERE `date` >= ?", array($date));
		}
		else
		{
			$query = $this->db->query("SELECT amount FROM daily_signups WHERE `date` >= ? AND `date` < ?", array($date, $next));
		}

		if($query->num_rows())
		{
			$row = $query->result_array();

			$total = 0;

			foreach($row as $item)
			{
				$total += $item['amount'];
			}

			return $total;
		}
		else
		{
			return 0;
		}
	}

	public function getSignupsDaily($type)
	{
		if($type == "today")
		{
			$date = date("Y-m-d");
		}
		else
		{
			$date = date("Y-m-d", time() - 60*60*24*30);
		}

		$query = $this->db->query("SELECT amount FROM daily_signups WHERE `date` >= ?", array($date));

		if($query->num_rows())
		{
			$row = $query->result_array();

			return $row[0]['amount'];
		}
		else
		{
			return 0;
		}
	}

	public function getGraph()
	{
		$query = $this->db->query("SELECT date, COUNT(DISTINCT ip) ipCount FROM visitor_log GROUP BY date");

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
}