@props(['content'])
<style>
    .toastui-editor-contents img {
        display: block;
        margin: 0 auto;
        width: 70%;
        height: auto;
        border-radius: 8px;
    }
</style>
<div x-data x-ref="viewerElement" x-init="new Viewer({
    el: $refs.viewerElement,
    viewer: true,
    initialValue: @js($content)
});">
</div>
