<?php

namespace App\Livewire;

use Livewire\Component;

class NewsHomepage extends Component
{
    public string $query = '';

    public bool $menuOpen = false;

    public bool $flashOpen = false;

    public function search(): void
    {
        $this->validate(['query' => ['required', 'string', 'min:2']]);
    }

    public function render()
    {
        return view('livewire.news-homepage', [
            'headlines' => [
                ['category' => 'कपिलवस्तु विशेष', 'title' => 'कपिलवस्तुमा अनौठो भाँडो र तामाको पुरानो सिक्का फेला', 'time' => '२०८३ श्रावण ५, मंगलवार', 'featured' => true],
                ['category' => 'शिक्षा', 'title' => 'सिद्धार्थ क्याम्पसमा शुल्क वृद्धि विवाद : मासिक र भर्ना शुल्क पुनरावलोकन गर्न विद्यार्थीको माग', 'time' => '२०८३ आश्विन ६, मंगलवार'],
                ['category' => 'मुख्य समाचार', 'title' => 'बसन्त कालाथोकी हत्या प्रकरणको छानविन गर्न सांसद घिमिरेको माग', 'time' => '२०८३ भाद्र ९, मंगलवार'],
                ['category' => 'राजनीति', 'title' => 'संविधान संशोधनबारे जनमोर्चा : संघीयता खारेज गर्न र राष्ट्रिय जनावर गैंडा बनाउन प्रस्ताव', 'time' => '२०८३ असार ३०, मंगलवार'],
                ['category' => 'अर्थ', 'title' => 'राष्ट्रिय वाणिज्य बैंकमा ६ नयाँ सञ्चालक नियुक्त, अर्थमन्त्रीको निर्णयमाथि प्रश्न', 'time' => '२०८३ असार ३०, मंगलवार'],
                ['category' => 'समाज', 'title' => 'स्थानीय तहमा सार्वजनिक सेवाको पहुँच विस्तार गर्ने तयारी', 'time' => '२०८३ आश्विन ४, शनिवार'],
            ],
        ]);
    }
}
