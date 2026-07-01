<?php

namespace App\Services;

use App\Repositories\ShoeRepository;

class ShoeService
{
    protected $repository;

    public function __construct(ShoeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getShoesStats($request)
    {
        return $this->repository->getShoesStats($request);
    }

    public function getShoeDetails($shoe, $isAjax = false)
    {
        return $this->repository->getShoeDetails($shoe, $isAjax);
    }

    public function updateShoe($request, $shoe)
    {
        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $uploadedFile = $request->file('image');
            $imageName = time() . '.' . $uploadedFile->getClientOriginalExtension();
            $destinationPath = public_path('images/small-thumbnail');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $uploadedFile->move($destinationPath, $imageName);
            $data['image'] = $imageName;
        }

        return $this->repository->updateShoe($shoe, $data);
    }

    public function getShoesDataQuery($request)
    {
        return $this->repository->getShoesDataQuery($request);
    }

    public function getImagesByIds(array $ids): array
    {
        return $this->repository->getImagesByIds($ids);
    }

    public function createImagesZip(array $imagePaths)
    {
        $zipFileName = 'images.zip';
        $zipDirectory = public_path('zip');
        $zipFilePath = $zipDirectory . '/' . $zipFileName;
        $publicZipFilePath = 'zip/' . $zipFileName;

        if (!file_exists($zipDirectory)) {
            mkdir($zipDirectory, 0755, true);
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($imagePaths as $imagePath) {
                $filePath = public_path('images/small-thumbnail/' . $imagePath);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
            
            return [
                'success' => true,
                'zip_file_path' => $zipFilePath,
                'public_zip_file_path' => asset($publicZipFilePath),
            ];
        }

        return ['success' => false];
    }
    
    public function deleteImagesZip($zipFilePath)
    {
        if (!empty($zipFilePath) && file_exists($zipFilePath)) {
            unlink($zipFilePath);
            return true;
        }
        return false;
    }

    public function getIndexData()
    {
        $totals = $this->repository->getInventoryTotals();
        
        $currentDate = \Carbon\CarbonImmutable::today();
        return [
            'stock' => calculateStockByDate($currentDate),
            'stock_purchase_price' => calculatePriceByDate($currentDate),
            'total_retail_price' => calculateRetailPriceByDate($currentDate),
        ];
    }

    public function getEditData()
    {
        return [
            'categories' => $this->repository->getSubCategories(),
            'colors' => $this->repository->getColors(),
        ];
    }
}
