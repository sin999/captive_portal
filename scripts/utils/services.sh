current_dir=$(pwd)
SCRIPT_PATH=`dirname "$0"`
cd  $SCRIPT_PATH
cd ..
source ./project.conf
source $commons_path/portal.conf
#Извлекаем номер порта из названия директории (линка) 
#например для /var/www/html/captive_portal/project_enabled/port8880/utils
# переменной site будет присвоено значение 8880 и это значене будет добавлено к назаванию сервиса 
# (вместо прейсхолдера)
script_dir="$(basename $(pwd))"
site=$server_for_service"${script_dir:4}"
echo  ${services/"##SITE_PLACEHOLDER##"/$site}
cd $current_dir


