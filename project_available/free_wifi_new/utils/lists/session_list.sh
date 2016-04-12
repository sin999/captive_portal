#read config
source ../../project.conf
mysql radius -N -e " select distinct acctsessionid,nasipaddress from  "$actions_table"  actions_table"`
	`" left join radacct on radacct.username=actions_table.login "`
	`" where  actions_table.action_id='"$action_name"' and (radacct.parentsessionid is null or radacct.parentsessionid='') "`
	`" and radacct.acctstoptime is null  and  not acctsessionid is null and (actions_table.last_shown<'2016-03-13 00:00:00' or actions_table.last_shown is null)"`
	`" "
