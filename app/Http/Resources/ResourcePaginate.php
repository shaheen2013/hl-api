<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

class ResourcePaginate extends ResourceCollection
{
    protected $meta;
    protected $links;
    protected $resourceFilter;
    protected $externalApi = false;

    public function __construct($resource)
    {
        // Define path on response in function of origin request
        if ($this->externalApi) {
            $resource = $this->replaceExternalApiUrl($resource);
        }

        // Mount paginate links and meta
        $paginated = $resource->toArray();
        // Meta
        $this->meta = Arr::except($paginated, [
            'data',
            'first_page_url',
            'last_page_url',
            'prev_page_url',
            'next_page_url',
        ]);
        // Links
        $this->links = [
            'first' => $paginated['first_page_url'] ?? null,
            'last' => $paginated['last_page_url'] ?? null,
            'prev' => $paginated['prev_page_url'] ?? null,
            'next' => $paginated['next_page_url'] ?? null,
        ];

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // Start response with data
        return [
            'data' => $this->resourceFilter,
            'meta' => $this->meta,
            'links' => $this->links,
        ];
    }

    /**
     * Replace paginate url with the externalApi url
     *
     * @param LengthAwarePaginator $resource
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function replaceExternalApiUrl($resource)
    {
        // Replace url/api with externalApi url
        $url = str_replace(config('app.url') . '/api', config('external-api.url'), url()->current());
        $resource->withPath($url);
        return $resource;
    }
}
