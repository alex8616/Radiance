<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\CategoriaTratamiento;
use App\Models\Sucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(UserSeeder::class);
        
        // Crear categorías de tratamientos
        CategoriaTratamiento::create([
            'nombre' => 'Ortodoncia',
            'descripcion' => 'Tratamientos relacionados con la ortodoncia.'
        ]);
        CategoriaTratamiento::create([
            'nombre' => 'Fisioterapia',
            'descripcion' => 'Tratamientos relacionados con la fisioterapia.'
        ]);

        //crear sucursales
        Sucursal::create([
            'nombre' => 'Sucursal Central',
            'direccion' => 'Av. Principal 123',
            'telefono' => '70000000',
            'latitud' => -16.500000,    
            'longitud' => -68.150000,
        ]);

        Sucursal::create([
            'nombre' => 'Sucursal Norte',
            'direccion' => 'Calle Norte 456',
            'telefono' => '70000002',
            'latitud' => -16.500000,
            'longitud' => -68.150000,
        ]);

        //crear doctores
        Doctor::create([
            'user_id' => 2,
            'nombre' => 'Dr. Alejandro',    
            'especialidad' => 'Ortodoncia',
            'telefono' => '70000002',
        ]);

        //crear relación doctor-sucursal
        $doctor = Doctor::find(1);
        $doctor->sucursales()->attach(1);

        //crear pacientes
        Paciente::create([
            'created_by' => 2,
            'nombre' => 'Paciente 1',
            'apellido_paterno' => 'Apellido Paterno 1',
            'apellido_materno' => 'Apellido Materno 1',
            'ci' => '12345679',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'La Paz',
            'telefono' => '70000003',
            'direccion' => 'Calle Principal 456',
            'ocupacion' => 'Ingeniero',
            'estado_civil' => 'Soltero',
            'sexo' => 'Masculino',
            'imagen' => 'paciente1.jpg'
        ]);

        Paciente::create([
            'created_by' => 2,
            'nombre' => 'Paciente 2',
            'apellido_paterno' => 'Apellido Paterno 2',
            'apellido_materno' => 'Apellido Materno 2',
            'ci' => '12345680',
            'fecha_nacimiento' => '1995-05-15',
            'lugar_nacimiento' => 'Cochabamba',
            'telefono' => '70000004',
            'direccion' => 'Avenida Secundaria 789',
            'ocupacion' => 'Abogado',
            'estado_civil' => 'Casado',
            'sexo' => 'Femenino',
            'imagen' => 'paciente2.jpg'
        ]);

        Paciente::create([
            'created_by' => 2,
            'nombre' => 'Paciente 3',
            'apellido_paterno' => 'Apellido Paterno 3',
            'apellido_materno' => 'Apellido Materno 3',
            'ci' => '12345681',
            'fecha_nacimiento' => '1985-10-20',
            'lugar_nacimiento' => 'Santa Cruz',
            'telefono' => '70000005',
            'direccion' => 'Calle Tercera 321',
            'ocupacion' => 'Médico',
            'estado_civil' => 'Divorciado',
            'sexo' => 'Masculino',
            'imagen' => 'paciente3.jpg'
        ]);
    }
}
