<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blogs_model extends CI_Model
{
    /////////
    // GET //
    /////////

    function getBlogs($aantal = 0, $pagina = 1)
    {
        $this->db->select('*');
        $this->db->from('blogs');
        $this->db->join('media', 'media.media_ID = blogs.media_ID', 'left');
        $this->db->order_by('blog_datum', 'desc');
        if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
        $query = $this->db->get();
        return $query->result();
    }

    function getBlogsUitgelicht() {
        $this->db->select('*');
        $this->db->from('blogs');
        $this->db->join('media', 'media.media_ID = blogs.media_ID', 'left');
        $this->db->order_by('blog_datum', 'desc');
        $this->db->where('blog_uitgelicht', 'ja');
        $query = $this->db->get();
        return $query->result();
    }

    function getBlogsUitgelichtGepubliceerd() {
        $this->db->select('*');
        $this->db->from('blogs');
        $this->db->join('media', 'media.media_ID = blogs.media_ID', 'left');
        $this->db->order_by('blog_datum', 'desc');
        $this->db->where('blog_uitgelicht', 'ja');
        $this->db->where('blog_publicatiedatum <=', date('Y-m-d'));
        $query = $this->db->get();
        return $query->result();
    }

    function getBlogsGepubliceerd($aantal = 0, $pagina = 1)
    {
        $this->db->select('*');
        $this->db->from('blogs');
        $this->db->join('media', 'media.media_ID = blogs.media_ID', 'left');
        $this->db->order_by('blog_datum', 'desc');
        $this->db->where('blog_gepubliceerd', 'ja');
        $this->db->where('blog_uitgelicht', 'nee');
        $this->db->or_where('blog_publicatiedatum <=', date('Y-m-d'));
        $this->db->where('blog_uitgelicht', 'nee');
        if($aantal > 0) $this->db->limit($aantal, (($pagina - 1) * $aantal));
        $query = $this->db->get();
        return $query->result();
    }

    function getBlogsAantal()
    {
        $this->db->select('*');
        $this->db->from('blogs');
        return $this->db->count_all_results();
    }

    function getBlogsGepubliceerdAantal()
    {
        $this->db->select('*');
        $this->db->from('blogs');
        $this->db->where('blog_gepubliceerd', 'ja');
        $this->db->where('blog_uitgelicht', 'nee');
        $this->db->or_where('blog_publicatiedatum <=', date('Y-m-d'));
        $this->db->where('blog_uitgelicht', 'nee');
        return $this->db->count_all_results();
    }

    function getBlogByID($item_ID)
    {
        $this->db->select('*');
        $this->db->from('blogs');
        $this->db->join('media', 'media.media_ID = blogs.media_ID', 'left');
        $this->db->where('blogs.blog_ID', $item_ID);
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function getBlogByURL($item_url)
    {
        $this->db->select('*');
        $this->db->from('blogs');
        $this->db->join('media', 'media.media_ID = blogs.media_ID', 'left');
        $this->db->where('blog_url', $item_url);
        $this->db->where('blog_gepubliceerd', 'ja');
        $query = $this->db->get();
        if($query->num_rows() > 0) return $query->row();
        else return null;
    }

    function zoekBlogs($zoekterm)
    {
        $this->db->select('*');
        $this->db->from('blogs');
        $this->db->like('blog_titel', $zoekterm);
        $this->db->or_like('blog_deelnemer', $zoekterm);
        $query = $this->db->get();
        return $query->result();
    }



    ////////////
    // INSERT //
    ////////////

    function insertBlog($data)
    {
        $this->db->insert('blogs', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }



    ////////////
    // UPDATE //
    ////////////

    function updateBlog($item_ID, $data, $NoChange = false)
    {
        // Added to return correct status even without affected changes
        if($noChange) return $this->db->where('blog_ID', $item_ID)->update('blogs', $data);

        $this->db->where('blog_ID', $item_ID);
        $this->db->update('blogs', $data);
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }



    ////////////
    // DELETE //
    ////////////

    function deleteBlog($item_ID)
    {
        $this->db->where('blog_ID', $item_ID);
        $this->db->delete('blogs');
        if($this->db->affected_rows() == 1) return true;
        else return false;
    }
}