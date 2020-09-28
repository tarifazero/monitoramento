@servers(['web' => ['capanema@compartilhado-1.horizontes.info']])

@task('deploy', ['on' => 'web'])
	cd ~/web/monitoramento.tarifazerobh.org/public_html

	php7.4 artisan down --render="errors::503"

	git pull

    php7.4 $(which composer) install

	php7.4 artisan cache:clear

	php7.4 artisan storage:link

	php7.4 artisan config:cache

	php7.4 artisan view:cache

	php7.4 artisan up
@endtask
