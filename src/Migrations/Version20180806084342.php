<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180806084342 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $hash = '$2y$12$wa38EbQ4h3.nK/QjYmHo4.z2Q5GHGl236ozCuPNaQ8m.uYsGDJYxC';
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("INSERT INTO public.client (id, title, alias, sender_email, allow_ips, client_secret, is_active, created_at, updated_at) VALUES (1, 'mailer', 'mailer', 'no-reply@nag.ru', null, '181b21a16ecef7472c05543164e6b5f3', true, '2018-08-06 09:54:25', '2018-08-06 09:55:15');");
        $this->addSql("INSERT INTO public.dispatch_status (id, name, alias, editable) VALUES (1, 'Черновик', 'raw', true);");
        $this->addSql("INSERT INTO public.dispatch_status (id, name, alias, editable) VALUES (2, 'Готова к отправке', 'ready', true);");
        $this->addSql("INSERT INTO public.dispatch_status (id, name, alias, editable) VALUES (3, 'В процессе отправки', 'process', null);");
        $this->addSql("INSERT INTO public.dispatch_status (id, name, alias, editable) VALUES (4, 'Завершена', 'complete', null);");
        $this->addSql("INSERT INTO public.user (id, fullname, email, password, is_active) VALUES (1, 'Ноль пользователь', 'user@null.ru', '{$hash}', true)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
    }
}

;
