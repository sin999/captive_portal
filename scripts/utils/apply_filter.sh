#накладывает сервисы на все сессии попадающие под филтр ( из всех сессий попадающих под акцию выбирает те у которх
# либо логин либо номер сессии попадает под grep) 
# использование  ./apply_filter.sh 10166685 или  ./apply_filter.sh test5
current_dir=$(pwd)
SCRIPT_PATH=`dirname "$0"`
cd  $SCRIPT_PATH
#read config
project_dir=$(dirname $PWD)
source $project_dir/project.conf
./session_list.sh | grep $1 | $project_dir/utils/applyRemoveAll.php $project_dir apply 
cd $current_dir
