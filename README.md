MonologExtenderBundle
=============

The `MonologExtenderBundle` provides integration of the [MonologExtender](https://github.com/stuzzo/monolog-bundle)
library into the Symfony framework.

Configuration
=============

This is an example configuration to use mail, stream and html formatter.

```yaml
monolog:
    handlers:
        main_critical:
            type:           fingers_crossed
            action_level:   debug
            handler:        grouped
            excluded_404s:
                            - ^/
        grouped:
            type:           group
            members:        [streamed_error, deduplicated, slackwebhook]
        streamed_error:
            type:           rotating_file
            max_files:      5
            path:           "%kernel.logs_dir%/%kernel.environment%_error.log"
            level:          error
            formatter:      stuzzo.logger.stream.formatter
        deduplicated:
            type:           deduplication
            handler:        swift
        swift:
            type:           swift_mailer
            from_email:     %mailer_sender%
            to_email:       %mailer_to%
            subject:        "[PROJECT] - Error %kernel.environment%"
            level:          error
            formatter:      stuzzo.logger.html.formatter
            content_type:   text/html
        slackwebhook:
            type:           slackwebhook
            channel:        %channel%
            webhook_url:    %hook_url%
            level:          critical
            include_extra:  true
```

This is an example configuration to use slack and html processors.

```yaml
services:
    app.processor.slack:
        class: Stuzzo\Monolog\Processor\SlackProcessor
        arguments: ["@security.token_storage"]
        tags:
          - { name: monolog.processor, handler: slackwebhook }
          - { name: kernel.event_listener, event: kernel.request, method: onKernelRequest }

    app.processor.web:
        class: Stuzzo\Bundle\MonologExtenderBundle\Processor\ExtendedWebUserDataProcessor
        arguments: ["@security.token_storage"]
        tags:
          - { name: monolog.processor, handler: grouped }
          - { name: kernel.event_listener, event: kernel.request, method: onKernelRequest }
```

License
=======

This bundle is released under the [MIT license](LICENSE)
