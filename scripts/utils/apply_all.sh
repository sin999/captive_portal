#накладывает сервисы на все активные  сессии  абонентов участвующих в акции
current_dir=$(pwd)
SCRIPT_PATH=`dirname "$0"`
cd  $SCRIPT_PATH
#read config
project_dir=$(dirname $PWD)
source $project_dir/project.conf
./session_list.sh | $project_dir/utils/applyRemoveAll.php $project_dir apply 
cd $current_dir
