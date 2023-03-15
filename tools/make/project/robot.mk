PHONY += test-robot
test-robot: ## Run Robot framework tests in docker container
	docker run \
			-v C:/hel-lomake-avustus-repos2022/drupal-helfi-form-tool/test/tests/logs:/opt/robotframework/reports:Z \
			-v C:/hel-lomake-avustus-repos2022/drupal-helfi-form-tool/test/tests:/opt/robotframework/tests:Z \
			-v C:/hel-lomake-avustus-repos2022/drupal-helfi-form-tool/test/resources:/opt/robotframework/resources:Z \
			-v C:/hel-lomake-avustus-repos2022/drupal-helfi-form-tool:/outputdir \
			-e TEST_BASEURL=https://www.hel.fi/fi/test-lomakkeet/todistusjaljennospyynto-tilaus/ \
      		-e ROBOT_OPTIONS="--loglevel DEBUG" \
			-e ROBOT_OPTIONS="-d C:/hel-lomake-avustus-repos2022/drupal-helfi-form-tool/test/tests/logs" \
			-e ROBOT_OPTIONS="--variable environment:test-chrome" \
			-e ROBOT_OPTIONS="--variable azure-browser-sleep:1" \
			-e ROBOT_OPTIONS="--exitonfailure" \
			-e ROBOT_OPTIONS="--include regression" \
			ppodgorsek/robot-framework:latest
			
			-e ROBOT_OPTIONS="lomake*.robot" \

## $(DRUPAL_HOSTNAME)regression
## ${ROBOT_OPTIONS}
##			--add-host $(DRUPAL_HOSTNAME):127.0.0.1 \
##			--net="host" \
##			-it \
# -e ROBOT_OPTIONS="-d C:/hel-lomake-avustus-repos2022/drupal-helfi-form-tool/test/tests/logs" \
#-e ROBOT_OPTIONS="-d C:/hel-lomake-avustus-repos2022/drupal-helfi-form-tool/test/tests/logs --variable environment:test-chrome --variable azure-browser-sleep:1 --exitonfailure --include mail" \