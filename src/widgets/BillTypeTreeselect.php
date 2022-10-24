<?php
declare(strict_types=1);

namespace hipanel\modules\finance\widgets;

use hipanel\helpers\StringHelper;
use hipanel\modules\finance\assets\VueTreeselectAsset;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class BillTypeTreeselect extends InputWidget
{
    /**
     * @var array<string, string> $billTypes - list of bill types, where key – is name and value – is label
     */
    public array $billTypes = [];
    public ?string $replaceAttribute = null;

    public function run(): string
    {
        VueTreeselectAsset::register($this->view);
        $id = $this->getId();
        $options = $this->buildOptionsArray();
        $value = Html::getAttributeValue($this->model, $this->replaceAttribute ?? $this->attribute);
        $this->registerJs($id);
        $activeInput = Html::activeHiddenInput($this->model, $this->attribute, [
            'v-model' => 'value',
            'value' => null,
            'data' => [
                'value' => $value,
                'options' => Json::encode($options),
            ],
        ]);

        return sprintf(/** @lang HTML */ '
            <div id="%s">
                <treeselect
                  :options="options"
                  :disable-branch-nodes="true"
                  :show-count="true"
                  :always-open="false"
                  :append-to-body="true"
                  search-nested
                  placeholder="----"
                  v-model="value"
                >
                    <div slot="value-label" slot-scope="{ node }" v-html="node.raw.treeLabel ?? node.raw.label"></div>
                </treeselect>
                %s
            </div>
        ',
            $id,
            $activeInput);
    }

    public function registerJs(string $widgetId): void
    {
        $this->view->registerJs(
            sprintf(/** @lang JavaScript */ "
                ;(() => {
                    const container = $('#%s');
                    new Vue({
                        el: container.get(0),
                        data: {
                            value: container.find('input[type=hidden]').data('value'),
                            options: container.find('input[type=hidden]').data('options')
                        }
                    });
                })();
                ",
                $widgetId
            )
        );
    }

    private function buildOptionsArray(): array
    {
        $types = $this->billTypes;
        // Each type key is a string like "monthly,hardware" or "monthly,leasing,server"
        // We need to split it by comma and build a recursive array of options for vue-treeselect, where ID is a type name
        $options = [];
        foreach ($types as $type => $label) {
            $typeParts = explode(',', $type);
            $currentLevel = &$options;
            foreach ($typeParts as $i => $typePart) {
                // skip last part, because it is a type name
                if ($i === count($typeParts) - 1) {
                    $currentLevel = &$currentLevel['children'][$typePart];
                    break;
                }
                if (!isset($currentLevel['children'][$typePart])) {
                    $currentLevel['children'][$typePart] = [
                        'id' => $typePart,
                        'label' => $typePart,
                        'children' => [],
                    ];
                }
                $currentLevel = &$currentLevel['children'][$typePart];
            }
            $currentLevel = [
                'id' => $type,
                'label' => $label,
                'treeLabel' => str_contains($type, ',') ? $this->findTreeLabel($type, $types) : null,
                'isDisabled' => str_contains($type, 'delimiter'),
            ];
        }

        // Remove all keys in children array recursively, because vue-treeselect expects only array of options
        $result = $this->removeKeysRecursively(array_values($options['children']));

        return $result;
    }

    private function removeKeysRecursively(array $items): array
    {
        foreach ($items as &$item) {
            if (isset($item['children'])) {
                $item['children'] = $this->removeKeysRecursively(array_values($item['children']));
            }
        }

        return $items;
    }

    private function findTreeLabel(string $type, array $types): ?string
    {
        $parts = [];
        $chunks = explode(',', $type);
        $key = '';
        foreach ($chunks as $part) {
            $key .= empty($key) ? $part : ',' . $part;
            if (isset($types[$key]) && $key !== $type) {
                $parts[$key] = Html::tag('span', StringHelper::truncate($this->fixLang($types[$key]), 10));
            }
        }
        $parts[] = $this->fixLang($types[$type]);

        return !empty($parts) ? implode("", $parts) : null;
    }

    private function fixLang(string $text): string
    {
        if (empty($text)) {
            return $text;
        }

        return Yii::$app->getI18n()->removeLegacyLangTags($text);
    }
}