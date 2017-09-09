<?php

use App\Models\Book;
use App\Models\Material;
use App\Models\Author;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv_file = $this->getCsv();

        foreach ($csv_file as $value) {
            $check_if_exist_material = Material::where('name', $value['Material'])->first();

            $barcode = date('Ymd').sprintf("%06d", mt_rand(1, 999999));

            if (!$check_if_exist_material) {
                $material = Material::create([
                  'name' => $value['Material']
                ]);
                $material->save();
            } else {
                $material = $check_if_exist_material;
            }

            $author_arr = array($value['Author'], $value['Author1'], $value['Author2'], $value['Author3']);
            $subject_arr = array($value['Subject1'], $value['Subject2'], $value['Subject3'], $value['Subject4'], $value['Subject5']);

            $author_ids = [];
            $subject_ids = [];

            foreach ($author_arr as $author) {
                $check_author = Author::where('name', $author)->first();

                if (! $check_author) {

                        $author_data = Author::firstOrCreate([
                          'name' => $author,
                        ]);
                        $author_data->save();
                        $author_ids[] = $author_data->id;

                } else {
                    $author_ids[] = $check_author->id;
                }

            }

            foreach ($subject_arr as $subject) {
                $check_subject = Subject::where('name', $subject)->first();

                if (! $check_subject) {
                    if ($subject != '') {
                        $subject_data = Subject::firstOrCreate([
                          'name' => $subject,
                        ]);

                        $subject_data->save();
                        $subject_ids[] = $subject_data->id;
                    }

                    $prev_subject = $subject_data;

                    if ($subject == '') {
                        $author_ids[] = $prev_subject->id;
                    }
                } else {
                    $subject_ids[] = $check_subject->id;
                }
            }

            $save_barcode = '';
            $barcode = date('Ymd').sprintf("%06d", mt_rand(1, 999999));

            $check_barcode = Book::where('barcode', '=', $barcode)->first();

            if ($check_barcode) {
                $new_barcode = date('Ymd').sprintf("%06d", mt_rand(1, 999999));
                $save_barcode = $new_barcode;
                echo $barcode . ' -- ' .$new_barcode;
            } else {
                $save_barcode = $barcode;
            }


            $book = Book::create([
              'title'               => $value['Title'],
              'barcode'             => $save_barcode,
              'publisher'           => $value['Publisher'],
              'published_year'      => $value['Pdate'],
              'card_number'         => $value['ControlNo'],
              'call_number'         => $value['CallNo'],
              'quantity'            => 1,
              'available_quantity'  => 1,
              'material_id'         => $material->id,
              'isbn'                => $value['ISBN'],
              'etal'                => $value['EtAL'],
              'edition'             => $value['Edition'],
              'publish_place'       => $value['PubPlace'],
              'physical_desc'       => $value['PhysicalDesc'],
              'aetitle'             => $value['AETitle'],
              'stitle'              => $value['STitle'],
              'note'                => $value['Note'],
              'book_level'          => $value['BookLevel'],
              'editor'              => $value['Editor'],
              'illustrator'         => $value['Illustrator'],
              'compiler'            => $value['Compiler']
            ]);

            $book->save();

            $book->authors()->sync($author_ids);
            $book->subjects()->sync($subject_ids);

            echo 'Book ID ' .$book->id . ' has been saved to database' . "\n";
        }

    }

    private function getCsv()
    {
        $file = storage_path('app/spulib-books.csv');

        $csv = [];
        $row = 1;
        $header = null;

        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($header === null) {
                    $header = $data;
                    continue;
                }

                $csv[] = array_combine($header,$data);
                $num = count($data);
                $row++;
            }
            fclose($handle);
        }

        return $csv;
    }
}
