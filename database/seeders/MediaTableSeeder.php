<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MediaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('media')->delete();
        
        \DB::table('media')->insert(array (
            0 => 
            array (
                'id' => 1,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 1,
                'uuid' => '078d440c-9d56-4ed4-ba75-7263a06f5042',
                'collection_name' => 'note-attachments',
                'name' => '20251201_180627',
                'file_name' => '20251201_180627.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 2893672,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-12-01 18:08:00',
                'updated_at' => '2025-12-01 18:08:00',
            ),
            1 => 
            array (
                'id' => 2,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 3,
                'uuid' => 'f075e93f-41aa-4293-a3e2-4f4e1ac991cd',
                'collection_name' => 'note-attachments',
            'name' => 'Stock(1)',
            'file_name' => 'Stock(1).xlsx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 4733143,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-12-30 17:02:53',
                'updated_at' => '2025-12-30 17:02:53',
            ),
            2 => 
            array (
                'id' => 3,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 4,
                'uuid' => 'e8878ae9-7730-4ac7-a5ae-16036f42d3fb',
                'collection_name' => 'note-attachments',
                'name' => 'Avijatry Yearly Calculation 2024-25',
                'file_name' => 'Avijatry-Yearly-Calculation-2024-25.xlsx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 18778,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-12-30 17:04:01',
                'updated_at' => '2025-12-30 17:04:01',
            ),
            3 => 
            array (
                'id' => 4,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 5,
                'uuid' => '9463b12b-b1b6-4e5d-9e77-8db281a7072e',
                'collection_name' => 'note-attachments',
                'name' => 'Bank Authorization Latter',
                'file_name' => 'Bank-Authorization-Latter.docx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 14105,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-12-30 17:04:34',
                'updated_at' => '2025-12-30 17:04:34',
            ),
            4 => 
            array (
                'id' => 5,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 5,
                'uuid' => 'fa585eea-5090-437c-9cf8-4a761f58d922',
                'collection_name' => 'note-attachments',
                'name' => 'Rafael Dutch Bangla Bank Credit Card Authorization',
                'file_name' => 'Rafael-Dutch-Bangla-Bank-Credit-Card-Authorization.docx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 14449,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 2,
                'created_at' => '2025-12-30 17:04:34',
                'updated_at' => '2025-12-30 17:04:34',
            ),
            5 => 
            array (
                'id' => 6,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 6,
                'uuid' => 'aa697546-1683-427b-a518-1a14df94bdc7',
                'collection_name' => 'note-attachments',
                'name' => 'Invoice Final Design',
                'file_name' => 'Invoice-Final-Design.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 5733855,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-12-30 17:05:02',
                'updated_at' => '2025-12-30 17:05:02',
            ),
            6 => 
            array (
                'id' => 7,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 7,
                'uuid' => 'c252f3ac-6e91-4037-b554-42e04ee5e648',
                'collection_name' => 'note-attachments',
                'name' => 'Avijatry Staff Account List',
                'file_name' => 'Avijatry-Staff-Account-List.xlsx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 12299,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-12-30 17:05:38',
                'updated_at' => '2025-12-30 17:05:38',
            ),
            7 => 
            array (
                'id' => 11,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 8,
                'uuid' => 'd314ef56-499b-49f7-8ded-97762c6a1d03',
                'collection_name' => 'note-attachments',
                'name' => 'Daily Report 25-26',
                'file_name' => 'Daily-Report-25-26.xlsx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 253034,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2026-02-17 14:30:53',
                'updated_at' => '2026-02-17 14:30:53',
            ),
            8 => 
            array (
                'id' => 12,
                'model_type' => 'App\\Models\\Note',
                'model_id' => 11,
                'uuid' => '9201b38c-d432-4dfb-bfc9-8e8c92413677',
                'collection_name' => 'note-attachments',
                'name' => 'Avijatry Soft. Correction',
                'file_name' => 'Avijatry-Soft.-Correction.docx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 343270,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2026-02-19 12:54:56',
                'updated_at' => '2026-02-19 12:54:56',
            ),
        ));
        
        
    }
}