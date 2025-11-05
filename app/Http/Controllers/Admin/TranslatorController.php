<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Translator;
use Illuminate\Http\Request;
use App\Services\TranslatorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\AdminController;
use Illuminate\Contracts\Foundation\Application;

class TranslatorController extends AdminController
{
    protected string $module = 'translators';

    public function index(): Factory|View|Application
    {
        /** @var TranslatorService $service */
        $service = resolve(TranslatorService::class);

        $languages = $service->getAllLocales();
        $translations = [];

        $translationsModel = Translator::query()->with('translations')->get();

        foreach ($languages as $language) {

            $trans = $service->getAllTranslations($language);
            foreach ($trans as $field => $value) {

                $translations[$field][$language] = $translationsModel->where('key', $field)->count() ?
                    $translationsModel->where('key', $field)->first()->translate($language)->value :
                    $value;

            }

        }

        return $this->view('admin.views.' . $this->module . '.index', [
            'languages'     => $languages,
            'translations'  => $translations,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token'])['key'];

        foreach ($inputs as $key => $data) {

            Translator::query()->updateOrCreate(['key' => $key], $data);

        }

        $this->notify()->addUpdated($this->module);

        return redirect()->route('admin.' . $this->module . '.index');
    }
}
