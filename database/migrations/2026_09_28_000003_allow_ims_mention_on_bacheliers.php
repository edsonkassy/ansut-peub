<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Ajoute la mention « IMS » (moins de 200 points) : la colonne `mention` était un ENUM
 * (passable, assez_bien, bien, tres_bien). On la transforme en VARCHAR(20) nullable ;
 * les valeurs autorisées restent contrôlées par la validation applicative.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE bacheliers MODIFY mention VARCHAR(20) NULL');
            return;
        }

        if ($driver === 'sqlsrv') {
            // SQL Server : l'ENUM est un CHECK constraint qu'il faut retirer avant de changer le type
            DB::unprepared("
                DECLARE @sql NVARCHAR(MAX) = '';
                SELECT @sql += 'ALTER TABLE bacheliers DROP CONSTRAINT ' + QUOTENAME(cc.name) + ';'
                FROM sys.check_constraints cc
                JOIN sys.columns c ON c.object_id = cc.parent_object_id AND c.column_id = cc.parent_column_id
                WHERE cc.parent_object_id = OBJECT_ID('bacheliers') AND c.name = 'mention';
                EXEC sp_executesql @sql;
            ");
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE bacheliers DROP CONSTRAINT IF EXISTS bacheliers_mention_check');
        }

        Schema::table('bacheliers', function (Blueprint $table) {
            $table->string('mention', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Volontairement vide : revenir à l'ENUM supprimerait les mentions « ims » déjà enregistrées.
    }
};
